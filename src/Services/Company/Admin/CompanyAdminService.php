<?php

namespace DaydreamLab\User\Services\Company\Admin;

use DaydreamLab\User\Events\UpdateCompanyUsersUserGroupAndEdmEvent;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Jobs\ImportCompany;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Repositories\Company\Admin\CompanyAdminRepository;
use DaydreamLab\User\Repositories\Company\CompanyCategoryRepository;
use DaydreamLab\User\Services\Company\CompanyService;
use Illuminate\Support\Collection;

class CompanyAdminService extends CompanyService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    protected $companyCategoryRepository;

    public function __construct(
        CompanyAdminRepository $repo,
        CompanyCategoryRepository $companyCategoryRepository
    ) {
        parent::__construct($repo);
        $this->repo = $repo;
        $this->companyCategoryRepository = $companyCategoryRepository;
    }


    public function afterModify(Collection $input, $item)
    {
        # 根據公司的身份改變公司成員的群組 ex.經銷會員 -> 經銷會員
        if ($item->category != null) {
            if (in_array($item->category->title, ['經銷會員', '零壹員工'])) {
                $userGroup = UserGroup::where('title', '經銷會員')->first();
            } else {
                $userGroup = UserGroup::where('title', '一般會員')->first();
            }

            event(new UpdateCompanyUsersUserGroupAndEdmEvent($item, $userGroup));
        }
    }


    public function beforeAdd(Collection &$input)
    {
        $category = $this->companyCategoryRepository->find($input->get('category_id'));
        if (in_array($category->title, ['經銷會員', '零壹員工'])) {
            $input->put('status', EnumHelper::COMPANY_APPROVED);
            $input->put('approvedAt', now()->toDateTimeString());
        } elseif ($category->title == '一般') {
            $input->put('status', EnumHelper::COMPANY_NEW);
        } else {
            $input->put('status', EnumHelper::COMPANY_NONE);
        }
    }


    public function beforeModify(Collection &$input, &$item)
    {
        $inputCategory = $this->companyCategoryRepository->find($input->get('category_id'));
        if (in_array($item->category->title, ['經銷會員', '零壹員工', '競爭廠商', '原廠'])) {
            if ($inputCategory->title == '一般') {
                $input->put('status', EnumHelper::COMPANY_NEW);
                $input->put('approvedAt', null);
                $input->put('rejectedAt', null);
                $input->put('expiredAt', null);
            } elseif (in_array($inputCategory->title, ['原廠', '競爭廠商'])) {
                $input->put('status', EnumHelper::COMPANY_NONE);
                $input->put('approvedAt', null);
                $input->put('rejectedAt', null);
                $input->put('expiredAt', null);
            } else {
                $input->put('status', EnumHelper::COMPANY_APPROVED);
                $input->put('approvedAt', $item->approvedAt ?? now()->toDateTimeString());
                $input->put('rejectedAt', null);
            }
        } else {
            if ($inputCategory->title == '經銷會員') {
                $input->put('status', EnumHelper::COMPANY_APPROVED);
                $input->put('approvedAt', now()->toDateTimeString());
                $input->put('rejectedAt', null);
            } elseif (in_array($inputCategory->title, ['原廠', '競爭廠商'])) {
                $input->put('status', EnumHelper::COMPANY_NONE);
                $input->put('approvedAt', null);
                $input->put('rejectedAt', null);
                $input->put('expiredAt', null);
            } else {
                if (
                    $input->get('status') == EnumHelper::COMPANY_REJECTED
                    && $item->status != EnumHelper::COMPANY_REJECTED
                ) {
                    $input->put('rejectedAt', now()->toDateTimeString());
                    $input->put('approvedAt', null);
                    $input->put('expiredAt', null);
                }
            }
        }
    }


    public function export(Collection $input)
    {
        return $this->search($input);
    }


    public function store(Collection $input)
    {
        $company = $this->findBy('vat', '=', $input->get('vat'))->first();
        if ($company && !($input->get('id') && $input->get('id') == $company->id)) {
            $this->status = 'VatExists';
            $this->response = [];
            return $this->response;
        }
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
