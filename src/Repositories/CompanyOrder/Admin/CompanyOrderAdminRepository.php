<?php

namespace DaydreamLab\User\Repositories\CompanyOrder\Admin;

use DaydreamLab\User\Models\CompanyOrder\Admin\CompanyOrderAdmin;
use DaydreamLab\User\Repositories\CompanyOrder\CompanyOrderRepository;

class CompanyOrderAdminRepository extends CompanyOrderRepository
{
    public function __construct(CompanyOrderAdmin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
