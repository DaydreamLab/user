<?php

namespace DaydreamLab\User\Services\Company\Admin;

use DaydreamLab\User\Events\UpdateCompanyUsersUserGroupAndEdmEvent;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Jobs\ImportCompany;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Repositories\Company\Admin\CompanyAdminRepository;
use DaydreamLab\User\Repositories\Company\CompanyCategoryRepository;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\Company\CompanyService;
use Illuminate\Support\Collection;

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
                    $input->put('cancelAt', null);
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
