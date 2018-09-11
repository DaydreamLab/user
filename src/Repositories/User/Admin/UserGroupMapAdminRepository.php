<?php

namespace DaydreamLab\User\Repositories\User\Admin;

use DaydreamLab\User\Repositories\User\UserGroupMapRepository;
use DaydreamLab\User\Models\User\Admin\UserGroupMapAdmin;

class UserGroupMapAdminRepository extends UserGroupMapRepository
{
    public function __construct(UserGroupMapAdmin $model)
    {
        parent::__construct($model);
    }
}