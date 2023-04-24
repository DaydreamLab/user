<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Services\User\Admin\UserAdminService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use function Psy\sh;

class ImportUpdateUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->filePath);
        $sheet = $spreadsheet->getSheet(1);
        $maxRows = $sheet->getHighestRow();
        $rowsData = [];
        $maxIndex = ($this->rowIndex + 1) * 1000 + 2 < $maxRows
            ? ($this->rowIndex + 1) * 1000 + 2
            : $maxRows;
        for ($i = 2 + $this->rowIndex * 1000; $i < $maxIndex; $i++) {
            $data = [];
            $keys = [
                'companyName',
                'vat',
                'companyPhone',
                'companyEmail',
                'companyIndustry',
                'companyScale',
                'mobilePhone',
                'name',
                'companyEmail2',
                'department',
                'jobTitle',
                'purchaseRole',
                'interestIssues',
                'block',
                'blockReason'
            ];
            for ($j = 'B', $k = 0; $j <= 'P'; $j++, $k++) {
                $key = $j . $i;
                $data[$keys[$k]] = $sheet->getCell($key)->getValue();
            }
            $rowsData[] = $data;
        }

        $service = app(UserAdminService::class);
        foreach ($rowsData as $i => $rowData) {
            if ($rowData['mobilePhone']) {
                $user = $service->findBy('mobilePhone', '=', $rowData['mobilePhone'])->first();
                $userCompany = $user->company;
                $input = [];
                $input['importUpdateUser'] = 1;
                $phonesData = [];
                if ($rowData['companyPhone']) {
                    $phoneCode = explode('-', $rowData['companyPhone'][0]);
                    $phone = explode('#', explode('-', $rowData['companyPhone'])[1])[0];
                    $extArray = explode('#', explode('-', $rowData['companyPhone'])[1]);
                    $ext = count($extArray) > 1 ? $extArray[1] : $extArray[0];
                    if (is_array($userCompany->phones) && count($userCompany->phones)) {
                        $exist = false;
                        foreach ($userCompany->phones as $userCompanyPhones) {
                            if ($userCompanyPhones['phone'] == $phone) {
                                $exist = true;
                                break;
                            }
                        }
                        if (!$exist) {
                            $phonesData[] = [
                                'phoneCode' => $phoneCode,
                                'phone' => $phone,
                                'ext'   => $ext
                            ];
                        } else {
                            $phonesData = $userCompany->phones;
                        }
                    } else {
                        $phonesData[] = [
                            'phoneCode' => $phoneCode,
                            'phone' => $phone,
                            'ext'   => $ext
                        ];
                    }
                } else {
                    $phonesData = $userCompany->phones;
                }

                $input['company'] = [
                    'email' => $rowData['companyEmail'],
                    'vat' => $rowData['vat'] ?: null,
                    'name' => $rowData['companyName'],
                    'department' => $rowData['department'],
                    'jobTitle' => $rowData['jobTitle'],
                    'phones'    => $phonesData,
                    'purchaseRole' => $userCompany->purchaseRole ? $rowData['purchaseRole'] : null,
                    'interestIssues' => is_array($userCompany->interestIssues) && count($userCompany->interestIssues)
                        ? $userCompany->interestIssues
                        : (
                        (explode(',', $rowData['interestIssues'])[0])
                            ? explode(',', $rowData['interestIssues'])
                            : null
                        ),
                ];
                $input['subscribeNewsletter'] = $user->newsletterSubscription->newsletterCategories->count()
                    ? '1'
                    : '0';
                $service->modifyMapping($user, collect($input));
            }
        }
        // 刪除暫存檔
//        unlink($this->filePath);
    }
}
