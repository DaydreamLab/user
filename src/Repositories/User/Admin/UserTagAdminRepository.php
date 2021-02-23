<?php

namespace DaydreamLab\User\Repositories\User\Admin;

use DaydreamLab\User\Models\User\Admin\UserTagAdmin;
use DaydreamLab\User\Repositories\User\UserTagRepository;

class UserTagAdminRepository extends UserTagRepository
{
    protected $modelType = 'Admin';

    public function __construct(UserTagAdmin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}