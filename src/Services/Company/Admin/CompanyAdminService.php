<?php

namespace DaydreamLab\User\Services\Company\Admin;

use DaydreamLab\User\Jobs\ImportCompany;
use DaydreamLab\Cms\Repositories\Category\Admin\CategoryAdminRepository;
use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Repositories\Company\Admin\CompanyAdminRepository;
use DaydreamLab\User\Services\Company\CompanyService;
use Illuminate\Support\Collection;

class CompanyAdminService extends CompanyService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    protected $categoryAdminRepository;

    public function __construct(
        CompanyAdminRepository $repo,
        CategoryAdminRepository $categoryAdminRepository
    ) {
        parent::__construct($repo);
        $this->repo = $repo;
        $this->categoryAdminRepository = $categoryAdminRepository;
    }


    public function export(Collection $input)
    {
        $input->put('limit', 0);
        $input->put('paginate', 0);
        $companies = $this->search($input);

        $spreedsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreedsheet->getActiveSheet();

        $headers = ['公司名稱', '公司統編', '公司網址', '身份類別'];
        $h = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($h, 1, $header);
            $h+=1;
        }

        $r = 2;
        foreach ($companies as $company) {
            for ($i =1; $i<=count($headers); $i+=1) {
                switch ($i) {
                    case 1:
                        $v = $company->name;
                        break;
                    case 2:
                        $v = $company->vat;
                        break;
                    case 3:
                        $v = $company->domain;
                        break;
                    case 4:
                        $v = ($company->category) ? $company->category->title : '';
                        break;
                    default:
                        $v = '';
                        break;
                }
                $sheet->setCellValueExplicitByColumnAndRow($i, $r, $v, 's');
            }
            $r+=1;
        }

        $filename = 'company_export.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreedsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
        ob_clean();
        $writer->save('php://output');
    }


    public function add(Collection $input)
    {
        $this->checkCategoryId($input->get('category_id'));

        return parent::add($input);
    }


    public function afterModify(Collection $input, $item)
    {
        # 根據公司的身份改變公司成員的群組 ex.經銷會員 -> 經銷會員
        if ($item->category != null) {
            if ($item->category->title == '經銷會員') {
                $userGroup = UserGroup::where('title', '經銷會員')->first();

            } else {
                $userGroup = UserGroup::where('title', '一般會員')->first();
            }

            foreach ($item->userCompanies as $userCompany) {
//                $userCompany::update([
//                    'name' => $item->name,
//                    'vat' => $item->vat
//                ]);
                if ($user = $userCompany->user) {
                    $user->groups()->sync([$userGroup->id]);
                }
            }
        }
    }


    public function checkCategoryId($category_id)
    {
        if ($category_id) {
            $q = new QueryCapsule();
            $q->where('extension', 'Company')
                ->whereNotNull('parent_id');

            return $this->categoryAdminRepository->find($category_id, $q);
        } else {
            return  true;
        }
    }


    public function store(Collection $input)
    {
        $result = parent::store($input);
        if ($input->has('id')) {
            $result = $this->find($input->get('id'));
        }
        $this->response = $result;

        return $this->response;
    }


    public function importCompany($input)
    {
        $file = $input->file('file');
        $temp = $file->move('tmp', $file->hashName());
        $filePath = $temp->getRealPath();
        $job = new ImportCompany($filePath);

        dispatch($job);

        $this->status = 'ImportSuccess';
    }
}
