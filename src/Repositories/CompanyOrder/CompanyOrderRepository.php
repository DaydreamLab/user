<?php

namespace DaydreamLab\User\Repositories\CompanyOrder;

use DaydreamLab\User\Models\CompanyOrder\CompanyOrder;
use DaydreamLab\User\Repositories\UserRepository;

class CompanyOrderRepository extends UserRepository
{
    protected $modelName = 'CompanyOrder';

    public function __construct(CompanyOrder $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
