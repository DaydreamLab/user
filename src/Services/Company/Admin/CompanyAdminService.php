<?php

namespace DaydreamLab\User\Services\Company\Admin;

use DaydreamLab\Cms\Repositories\Category\Admin\CategoryAdminRepository;
use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Traits\LoggedIn;
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
}
