<?php

namespace DaydreamLab\User\Repositories\Role\Admin;

use DaydreamLab\User\Repositories\Role\RoleApiMapRepository;
use DaydreamLab\User\Models\Role\Admin\RoleApiMapAdmin;

class RoleApiMapAdminRepository extends RoleApiMapRepository
{
    public function __construct(RoleApiMapAdmin $model)
    {
        parent::__construct($model);
    }
}