<?php

namespace DaydreamLab\User\Repositories\CompanyOrderItem;

use DaydreamLab\User\Models\CompanyOrderItem\CompanyOrderItem;
use DaydreamLab\User\Repositories\UserRepository;

class CompanyOrderItemRepository extends UserRepository
{
    protected $modelName = 'CompanyOrderItem';

    public function __construct(CompanyOrderItem $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
