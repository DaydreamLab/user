<?php

namespace DaydreamLab\User\Repositories\User\Admin;

use DaydreamLab\User\Repositories\User\UserRoleMapRepository;
use DaydreamLab\User\Models\User\Admin\UserRoleMapAdmin;

class UserRoleMapAdminRepository extends UserRoleMapRepository
{
    public function __construct(UserRoleMapAdmin $model)
    {
        parent::__construct($model);
    }
}