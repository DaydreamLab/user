<?php

namespace DaydreamLab\User\Services\Company\Admin;

use Carbon\Carbon;
use DaydreamLab\Cms\Models\Brand\Brand;
use DaydreamLab\User\Events\UpdateCompanyUsersUserGroupAndEdmEvent;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Jobs\ImportCompany;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\CompanyOrder\CompanyOrder;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Repositories\Company\Admin\CompanyAdminRepository;
use DaydreamLab\User\Repositories\Company\CompanyCategoryRepository;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\Company\CompanyService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CompanyAdminService extends CompanyService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    protected $companyCategoryRepository;

    protected $userAdminRepository;

    public function __construct(
        CompanyAdminRepository $repo,
        CompanyCategoryRepository $companyCategoryRepository,
        UserAdminRepository $userAdminRepository
    ) {
        parent::__construct($repo);
        $this->repo = $repo;
        $this->companyCategoryRepository = $companyCategoryRepository;
        $this->userAdminRepository = $userAdminRepository;
    }


    public function afterModify(Collection $input, $item)
    {
        # 根據公司的身份改變公司成員的群組 ex.經銷會員 -> 經銷會員
        if ($item->category != null) {
            $userGroup = $item->category->title == EnumHelper::COMPANY_CATEGORY_NORMAL
                ? UserGroup::where('title', '一般會員')->first()
                : UserGroup::where('title', '經銷會員')->first();
            event(new UpdateCompanyUsersUserGroupAndEdmEvent($item, $userGroup));
        }
    }


    public function beforeAdd(Collection &$input)
    {
        $category = $this->companyCategoryRepository->find($input->get('category_id'));
        if ($category->title == EnumHelper::COMPANY_CATEGORY_DEALER) {
            if (!$input->get('approvedAt')) {
                $input->put('approvedAt', now()->toDateTimeString());
            }
        }
    }


    public function beforeModify(Collection &$input, &$item)
    {
        $inputCategory = $this->companyCategoryRepository->find($input->get('category_id'));

        if ($item->category->title != $inputCategory->title) {
            if ($inputCategory->title == EnumHelper::COMPANY_CATEGORY_DEALER) {
                if (!$input->get('approvedAt')) {
                    $input->put('approvedAt', now()->toDateTimeString());
                    $input->put('expiredAt', null);
                }
            }

            if ($inputCategory->title == EnumHelper::COMPANY_CATEGORY_NORMAL) {
                $input->put('approvedAt', null);
                $input->put('expiredAt', now()->toDateTimeString());
            }
        }
    }


    public function export(Collection $input)
    {
        return $this->search($input);
    }


    public function importOrder($input)
    {
        $file = $input->file('file');
        $temp = $file->move('tmp', $file->hashName());
        $filePath = $temp->getRealPath();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getSheet(0);
        $rows = $sheet->getHighestRow();

        $orderData = [];
        for ($i = 2; $i <= $rows; $i++) {
            $rowData = [];
            for ($j = 'A'; $j <= 'G'; $j++) {
                $key = $j . $i;
                $rowData[] = $sheet->getCell($key)->getValue();
            }
            $temp  = [
                'vat' => $rowData[3],
                'companyName' => $rowData[4],
                'brand' => $rowData[5],
                'date'  => $rowData[6],
                'row'   => $i
            ];
            $orderData[] = $temp;
        }

        $createData = [];
        $errors = [];
        $companyOrderData = collect($orderData)->groupBy('vat');
        foreach ($companyOrderData as $vat => $orders) {
            $company = Company::where('vat', $vat)->first();
            if (!$company) {
                $errors[] = [
                    'name' => $orders->first()['companyName'],
                    'vat' => $vat,
                    'reason' => '公司不存在',
                    'rows'  => $orders->pluck('row')->unique()->values()
                ];
                continue;
            }

            $errorRows = [];
            foreach ($orders as $order) {
                $brand = Brand::where('title', 'like', "{$order['brand']}")
                    ->orWhereJsonContains('params', ['subBrands' => $order['brand']])
                    ->first();
                if (!$brand) {
                    $errorRows[] = $order['row'];
                } else {
                    $date = Carbon::parse($order['date'] . '01', 'Asia/Taipei')
                        ->startOfDay()
                        ->tz('UTC')
                        ->toDateTimeString();
                    $companyOrder = CompanyOrder::where('brandId', $brand->id)
                        ->where('companyId', $company->id)
                        ->where('date', $date)
                        ->first();
                    if (!$companyOrder) {
                        $createData[] = [
                            'brandId' => $brand->id,
                            'companyId' => $company->id,
                            'date' => $date,
                            'created_by' => $this->getUser()->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            if (count($errorRows)) {
                $errors[] = [
                    'name' => $orders->first()['companyName'],
                    'vat' => $vat,
                    'reason' => '品牌不存在',
                    'rows'   => $errorRows
                ];
            }
        }
        unlink($filePath);

        if (count($errors)) {
            $message = '';
            foreach ($errors as $error) {
                foreach ($error['rows'] as $row) {
                    $message .= '列：' . $row . ' 原因：' . $error['reason'] . PHP_EOL;
                }
            }
            $this->response = $message;
            DB::rollBack();
        } else {
            DB::table('company_orders')->insert($createData);
        }

        $this->status = 'ImportOrder' . (count($errors) ? 'Fail' : 'Success');
    }


    public function store(Collection $input)
    {
        if ($input->get('categoryNote') !== '原廠') {
            $company = $this->findBy('vat', '=', $input->get('vat'))->first();
            if ($company && !($input->get('id') && $input->get('id') == $company->id)) {
                $this->status = 'VatExists';
                $this->response = [];
                return $this->response;
            }
        }

        $result = parent::store($input);
        if ($input->has('id')) {
            $result = $this->find($input->get('id'));
        }
        $this->response = $result;

        return $this->response;
    }


    public function searchUsers(Collection $input)
    {
        $users = $this->userAdminRepository->search($input);

        $this->status = 'SearchUserSuccess';
        $this->response = $users;

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
