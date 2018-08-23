<?php

namespace DaydreamLab\User\Repositories\User\Admin;

use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\User\Models\User\Admin\UserAdmin;

class UserAdminRepository extends UserRepository
{
    public function __construct(UserAdmin $model)
    {
        parent::__construct($model);
    }
}