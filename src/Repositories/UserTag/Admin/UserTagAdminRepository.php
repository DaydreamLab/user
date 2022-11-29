<?php

namespace DaydreamLab\User\Repositories\UserTag\Admin;

use DaydreamLab\User\Models\UserTag\Admin\UserTagAdmin;
use DaydreamLab\User\Repositories\UserTag\UserTagRepository;

class UserTagAdminRepository extends UserTagRepository
{
    public function __construct(UserTagAdmin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
