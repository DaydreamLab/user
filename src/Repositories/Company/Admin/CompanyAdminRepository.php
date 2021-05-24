<?php

namespace DaydreamLab\User\Repositories\Company\Admin;

use DaydreamLab\User\Repositories\Company\CompanyRepository;
use DaydreamLab\User\Models\Company\Admin\CompanyAdmin;

class CompanyAdminRepository extends CompanyRepository
{
    protected $modelType = 'Admin';

    public function __construct(CompanyAdmin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}