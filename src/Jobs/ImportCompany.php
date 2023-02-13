<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\Company\CompanyCategory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ImportCompany implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->filePath);
        $sheet = $spreadsheet->getSheetByName('公司');
        $rows = $sheet->getHighestRow();

        for ($i = 2; $i <= $rows; $i++) {
            $rowData = $this->getXlsxRowData($sheet, $i);
            // 創建獲取的資料

            $companycategory = $this->firstOrCreateCompanyCategory($rowData);
            $company = $this->firstOrCreateCompany($rowData, $companycategory);
        }

        // 刪除暫存檔
        unlink($this->filePath);
    }

    private function firstOrCreateCompanyCategory($rowData)
    {
        $categoryTitle = $rowData[3];

        $company = CompanyCategory::where('title', $categoryTitle)->first();

        if (! $company) {
            $alias = Str::uuid()->getHex();

            $company = CompanyCategory::create([
                'title' => $categoryTitle,
                'alias' => $alias,
                'path' => "/$alias",
                'introimage' => '',
                'introtext' => '',
                'image' => '',
                'description' => '',
                'state' => 1,
                'hits' => 0,
                'access' => 1,
                'ordering' => CompanyCategory::count() + 1,
                'params' => [],
                'extrafields' => [],
                'extrafields_search' => null,
                'locked_by' => 0,
                'created_by' => 1,
                'updated_by' => null,
                'locked_at' => null
            ]);
        }

        return $company;
    }

    private function firstOrCreateCompany($rowData, $companyCategory)
    {
        $vat = $rowData[0];
        $name = $rowData[1];

        if ($vat) {
            $company = company::where('vat', $vat)->first();
        } elseif ($name) {
            $company = Company::where('name', $name)->first();
        } else {
            // 沒統編也沒名字根本不能做
            return;
        }

        $data = [
            'vat' => $vat,
            'name' => $name,
            'domain' => $rowData[2],
            'category_id' => $companyCategory->id
        ];
        
        if ($company) {
            $company = $company->update($data);
        } else {
            $company = Company::create($data);
        }

        return $company;
    }



    private function getXlsxRowData($sheet, $rowNum)
    {
        $data = [];

        for($j = 'A'; $j <= 'S'; $j++) {
            $key = $j.$rowNum;
            $data[] = $sheet->getCell($key)->getValue();
        }

        return $data;
    }
}
