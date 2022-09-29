<?php

namespace DaydreamLab\User\Services\Company\Admin;

use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\User\Events\UpdateCompanyUsersUserGroupAndEdmEvent;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Jobs\ImportCompany;
use DaydreamLab\Cms\Repositories\Category\Admin\CategoryAdminRepository;
use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\Company\CompanyCategory;
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


    public function add(Collection $input)
    {
        $this->checkCategoryId($input->get('category_id'));

        return parent::add($input);
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


    public function beforeModify(Collection $input, &$item)
    {
        if ($input->get('status') == EnumHelper::COMPANY_APPROVED && $item->status != EnumHelper::COMPANY_APPROVED) {
            $input->put('approvedAt', now()->toDateTimeString());
            $input->put('rejectedAt', null);
            $dealerCategory = CompanyCategory::where('title', '=', '經銷會員')->first();
            $input->put('category_id', $dealerCategory->id);
        }

        if ($input->get('status') == EnumHelper::COMPANY_REJECTED && $item->status != EnumHelper::COMPANY_REJECTED) {
            $input->put('rejectedAt', now()->toDateTimeString());
            $input->put('approvedAt', null);
            $input->put('expiredAt', null);
            $normalCategory = CompanyCategory::where('title', '=', '一般會員')->first();
            $input->put('category_id', $normalCategory->id);
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


    public function export(Collection $input)
    {
        return $this->search($input);
    }


    public function store(Collection $input)
    {
        $company = $this->findBy('vat', '=', $input->get('vat'))->first();
        if ($company && ($input->get('id') && $input->get('id') != $company->id)) {
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
