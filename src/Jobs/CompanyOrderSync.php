<?php

namespace DaydreamLab\User\Jobs;

use Carbon\Carbon;
use DaydreamLab\Dsth\Notifications\DeveloperNotification;
use DaydreamLab\Media\Traits\Service\AzureBlob;
use DaydreamLab\User\Notifications\CompanyOrderSyncReportNotification;
use DaydreamLab\User\Services\Company\Admin\CompanyAdminService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CompanyOrderSync implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use AzureBlob;

    public $timeout = 900;

    protected $service;

    protected $saleContainer = 'monthlysales';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected ?string $path = null)
    {
        $this->onQueue('import-job');
        $this->service = app(CompanyAdminService::class);
    }


    public function download($path)
    {
        $client = $this->getAzureClient();
        $blobs = $client->listBlobs($this->saleContainer);

        $startOfMonth = now('Asia/Taipei')->startOfMonth();
        $endOfMonth = now('Asia/Taipei')->endOfMonth();

        $targets = [];
        # 先找出本月上傳的檔案
        foreach ($blobs->getBlobs() as $blob) {
            $create_date = $blob->getProperties()->getLastModified();
            show($create_date);
            if (Carbon::parse($create_date)->tz('Asia/Taipei')->between($startOfMonth, $endOfMonth)) {
                $targets[] = $blob;
            }
        }

        if (count($targets)) {
            // 找出最新的
            $newest = $targets[0];
            foreach ($targets as $target) {
                if (
                    Carbon::parse($target->getProperties()->getCreationTime())
                    > Carbon::parse($newest->getProperties()->getCreationTime())
                ) {
                    $newest = $target;
                }
            }

            $target = $client->getBlob($this->saleContainer, $newest->getName());
            $content = $target->getContentStream();
            file_put_contents("company_order.xls", $content);
        } else {
            Notification::route('mail', 'technique@daydream-lab.com')
                ->notify(new DeveloperNotification('[零壹]銷售紀錄未找到', '未找到上傳紀錄'));
            throw new \Exception('找不到本月銷售紀錄');
        }
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        ##### 這邊和 CompanyAdminService 高度相關 #####
        $filepath = $this->path ?: base_path('company_order.xls');
        if (!$this->path) {
            $this->download($filepath);
        }
        try {
            $spreadsheet = $this->service->getXlsx($filepath);
            unlink($filepath);

            $data = $this->service->getCompanyOrderDataFromXlsx($spreadsheet);

//        echo '新增銷售紀錄：' . count($data['data']) . '筆' . PHP_EOL;
            Log::info('新增銷售紀錄：' . count($data['data']) . '筆');
            # 新增銷售紀錄
            DB::table('company_orders')->insert($data['data']);

//        echo '更新銷售紀錄：' . count($data['existOrders']) . '筆'  . PHP_EOL;
            Log::info('更新銷售紀錄：' . count($data['existOrders']) . '筆');
            # 更新銷售紀錄
            foreach ($data['existOrders'] as $orderData) {
                DB::table('company_orders')->where('id', $orderData['id'])->update($orderData);
            }

//        echo '更新公司紀錄：' . count($data['existCompanies']) . '筆' . PHP_EOL;
            Log::info('更新公司紀錄：' . count($data['existCompanies']) . '筆');
            # 更新公司資料
            foreach ($data['existCompanies'] as $companyData) {
                DB::table('companies')->where('id', $companyData['id'])->update($companyData);
            }

            $errorsReason = $this->service->formatErrors($data['errors']);
//        echo '更新資料失敗：' . count($errorsReason) . '筆' . PHP_EOL;
            Log::info('更新資料失敗：' . count($errorsReason) . '筆');

            # 寄送失敗資料
            $mailResult = [
                'addOrder' => count($data['data']),
                'updateOrder' => count($data['existOrders']),
                'updateCompany' => count($data['existCompanies']),
                'fail' => count($errorsReason),
                'errors' => $errorsReason
            ];
            Notification::route('mail', ['marketing@zerone.com.tw'])
                ->notify(new CompanyOrderSyncReportNotification($mailResult, '零壹行銷企劃中心'));
            Notification::route('mail', ['technique@daydream-lab.com', 'jordan@daydream-lab.com'])
                ->notify(new CompanyOrderSyncReportNotification($mailResult, '白日夢工程部'));
        } catch (\Exception $exception) {
            Notification::route('mail', 'technique@daydream-lab.com')
                ->notify(new DeveloperNotification('[零壹]同步銷售紀錄失敗', '更新過程失敗'));
            throw new \Exception('同步銷售紀錄失敗');
        }
    }
}
