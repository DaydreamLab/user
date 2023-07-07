<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ImportNonePhoneUser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $filePath;

    protected $rowIndex;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath, $rowIndex)
    {
        $this->filePath = $filePath;
        $this->rowIndex = $rowIndex;
        $this->queue = 'import-job';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nonePhoneGroup = UserGroup::where('title', '無手機名單')->first();

        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->filePath);
        $sheet = $spreadsheet->getSheet(0);
        $maxRows = $sheet->getHighestRow();
        $rowsData = [];
        $maxIndex = ($this->rowIndex + 1) * 1000 + 1 < $maxRows
            ? ($this->rowIndex + 1) * 1000 + 1
            : $maxRows;
        for ($i = 1 + $this->rowIndex * 1000; $i < $maxIndex; $i++) {
            $data = [];
            $keys = [
                'companyName',
                'name',
                'vat',
                'companyEmail',
                'department',
                'jobTitle',
                'companyPhoneCode',
                'companyPhone',
                'companyPhoneExt',
                'mobilePhone',
                'industry',
            ];
            for ($j = 'B', $k = 0; $j <= 'L'; $j++, $k++) {
                $key = $j . $i;
                $data[$keys[$k]] = $sheet->getCell($key)->getValue();
            }
            $rowsData[] = $data;
        }

        $service = app(UserAdminService::class);
        foreach ($rowsData as $i => $rowData) {
            $userData = [
                'name' => $rowData['name'],
                'email' => $rowData['companyEmail'],
                'mobilePhone' => Str::lower(Str::random(20)),
                'activateToken' => 'importNonePhoneUser',
                'groupIds' => [$nonePhoneGroup->id],
                'subscribeNewsletter' => 0
            ];

            $userData['company'] = [
                'email' => $rowData['companyEmail'],
                'vat' => $rowData['vat'] ?: null,
                'department' => $rowData['department'],
                'jobTitle' => $rowData['jobTitle'],
            ];

            if ($rowData['companyPhoneCode'] || $rowData['companyPhone']) {
                $userData['company']['phones'] = [
                    [
                        'phoneCode' => $rowData['companyPhoneCode'],
                        'phone' => $rowData['companyPhone'],
                        'ext'   => $rowData['companyPhoneExt']
                    ]
                ];
            } else {
                $userData['company']['phones'] = [];
            }
            DB::transaction(function () use ($service, $userData) {
                $service->add(collect($userData));
            });
        }
        // 刪除暫存檔
//        unlink($this->filePath);
    }
}
