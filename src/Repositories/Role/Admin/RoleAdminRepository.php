<?php

namespace DaydreamLab\User\Repositories\Role\Admin;

use DaydreamLab\User\Repositories\Role\RoleRepository;
use DaydreamLab\User\Models\Role\Admin\RoleAdmin;

class RoleAdminRepository extends RoleRepository
{
    public function __construct(RoleAdmin $model)
    {
        parent::__construct($model);
    }
}