<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Notifications\CompanyOrderSyncReportNotification;
use DaydreamLab\User\Services\Company\Admin\CompanyAdminService;
use GuzzleHttp\Client;
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

    public $tries = 1;

    protected $service;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('import-job');
        $this->service = app(CompanyAdminService::class);
    }


    public function download($path)
    {
        $targetUrl = 'https://shyfood.com.tw/images/aaa.xlsx';
        $client = new Client();
        try {
            $response = $client->post($targetUrl, [
                'stream' => true,
                'timeout' => 180
            ]);
        } catch (\Throwable $t) {
            $response = null;
        }
        // 取得檔案二進位內容
        $body = $response->getBody();
        $fp = fopen($path, 'w');
        while (!$body->eof()) {
            fwrite($fp, $body->read(1024));
        }
        fclose($fp);
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        ##### 這邊和 CompanyAdminService 高度相關 #####
        $filepath = base_path('bbb.xlsx');
        $this->download($filepath);

        $spreadsheet = $this->service->getXlsx($filepath);
        unlink($filepath);

        $data = $this->service->getCompanyOrderDataFromXlsx($spreadsheet);

//        echo '新增銷售紀錄：' . count($data['data']) . '筆' . PHP_EOL;
        Log::info('新增銷售紀錄：' . count($data['data']) . '筆');
        # 新增銷售紀錄
        DB::table('company_orders')->insert($data['data']);

//        echo '更新銷售紀錄：' . count($data['existOrders']) . '筆' . PHP_EOL;
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
        Notification::route('mail', 'jordan@daydream-lab.com')
            ->notify(new CompanyOrderSyncReportNotification([
                'addOrder' => count($data['data']),
                'updateOrder' => count($data['existOrders']),
                'updateCompany' => count($data['existCompanies']),
                'fail' => count($errorsReason),
                'errors' => $errorsReason
            ], 'Jordan'));
    }
}
