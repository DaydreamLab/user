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
