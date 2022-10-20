<?php

namespace DaydreamLab\User\Repositories\CompanyOrderItem\Admin;

use DaydreamLab\User\Models\CompanyOrderItem\Admin\CompanyOrderItemAdmin;
use DaydreamLab\User\Repositories\CompanyOrderItem\CompanyOrderItemRepository;

class CompanyOrderItemAdminRepository extends CompanyOrderItemRepository
{
    public function __construct(CompanyOrderItemAdmin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
