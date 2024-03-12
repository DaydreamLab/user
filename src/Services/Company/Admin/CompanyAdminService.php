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


    public function formatErrors($errors): array
    {
        $errorsReason = [];
        foreach ($errors as $e) {
            foreach ($e['rows'] as $row) {
                $errorsReason[$row] = $e['reason'];
            }
        }

        return collect($errorsReason)->sortKeys()->all();
    }


    public function getCompanyOrderDataFromXlsx($spreadsheet)
    {
        $sheet = $spreadsheet->getSheet(0);
        $rows = $sheet->getHighestRow();

        $orderData = [];
        for ($i = 1; $i <= $rows; $i++) {
            $rowData = [];
            for ($j = 'A'; $j <= 'J'; $j++) {
                $key = $j . $i;
                $rowData[] = $sheet->getCell($key)->getValue();
            }
            $temp  = [
                'date'  => $rowData[0],
                'vat' => $rowData[1],
                'companyName' => $rowData[2],
                'phone' => $rowData[3],
                'zipcode' => $rowData[4],
                'address' => $rowData[5],
                'shippingAddress' => $rowData[6],
                'brandCode' => $rowData[7],
                'brand' => $rowData[8],
                'total' => $rowData[9],
                'row'   => $i
            ];
            $orderData[] = $temp;
        }

        $createData = [];
        $existCompanies = [];
        $existOrders = [];
        $errors = [];
        $user = $this->getUser();
        $companyOrderData = collect($orderData)->groupBy('vat');
        foreach ($companyOrderData as $vat => $orders) {
            if ($vat === '') {
                $errors[] = [
                    'name' => $orders->first()['companyName'],
                    'vat' => $vat,
                    'reason' => '缺少公司統編',
                    'rows' => $orders->pluck('row')->unique()->values()
                ];
                continue;
            }

            $company = Company::where('vat', $vat)->first();
            if (!$company) {
                $errors[] = [
                    'name' => $orders->first()['companyName'],
                    'vat' => $vat,
                    'reason' => '公司不存在官網中',
                    'rows' => $orders->pluck('row')->unique()->values()
                ];
                continue;
//                # 這邊創建寫在這不太好，因Ariel說沒有的公司不用幫他創建先註解
//                $company = $this->add(collect([
//                    'name' => $orders->first()['companyName'],
//                    'vat' => $vat,
//                    'category_id' => 5,
//                ]));
            } else {
                $existCompanies[] = [
                    'id' => $company->id,
                    'name' => $orders->first()['companyName'],
                    'vat' => $vat,
                    'phone' =>  $orders->first()['phone'],
                    'zipcode' =>  $orders->first()['zipcode'],
                    'address' =>  $orders->first()['address'],
                    'shippingAddress' =>  $orders->first()['shippingAddress'],
                ];
            }

            $errorRows = [];
            $ignoreRows = [];
            foreach ($orders as $order) {
                if ($order['total'] <= 0) {
                    $ignoreRows[] = $order['row'];
                    continue;
                }

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
                            'created_by' => $user ? $user->id : 99999993,
                            'created_at' => now()->toDateTimeString(),
                            'updated_at' => now()->toDateTimeString(),
                        ];
                    } else {
                        $existOrders[] =  [
                            'id'  => $companyOrder->id,
                            'brandId' => $brand->id,
                            'companyId' => $company->id,
                            'date' => $date,
                            'updated_by' => $user ? $user->id : 99999993,
                            'updated_at' => now()->toDateTimeString(),
                        ];
                    }
                }
            }

            if (count($ignoreRows)) {
                $errors[] = [
                    'name' => $orders->first()['companyName'],
                    'vat' => $vat,
                    'reason' => '銷售金額 <= 0 被排除',
                    'rows'   => $ignoreRows
                ];
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

        return [
            'data' => $createData,
            'errors' => $errors,
            'existCompanies' => collect($existCompanies)->unique('vat')->all(),
            'existOrders'   => $existOrders
        ];
    }


    public function getXlsx($path)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);

        return $reader->load($path);
    }


    public function importOrder($input)
    {
        $file = $input->file('file');
        $temp = $file->move('tmp', $file->hashName());
        $filePath = $temp->getRealPath();

        $spreadsheet = $this->getXlsx($filePath);
        unlink($filePath);
        $result = $this->getCompanyOrderDataFromXlsx($spreadsheet);

        if (count($result['errors'])) {
            $message = '';
            $errors = $this->formatErrors($result['errors']);
            foreach ($errors as $row => $error) {
                $message .= '列：' . $row . ' 原因：' . $error . PHP_EOL;
            }
            $this->response = $message;
//            DB::rollBack();
        }
//        else {
        DB::table('company_orders')->insert($result['data']);
        foreach ($result['existOrders'] as $orderData) {
            DB::table('company_orders')->where('id', $orderData['id'])->update($orderData);
        }
        foreach ($result['existCompanies'] as $companyData) {
            DB::table('companies')->where('id', $companyData['id'])->update($companyData);
        }
//        }

//        $this->status = 'ImportOrder' . (count($result['errors']) ? 'Fail' : 'Success');
        $this->status = 'ImportOrderSuccess';
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
