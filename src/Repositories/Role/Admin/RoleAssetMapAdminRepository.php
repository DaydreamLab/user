<?php

namespace DaydreamLab\User\Repositories\Role\Admin;

use DaydreamLab\User\Repositories\Role\RoleAssetMapRepository;
use DaydreamLab\User\Models\Role\Admin\RoleAssetMapAdmin;

class RoleAssetMapAdminRepository extends RoleAssetMapRepository
{
    public function __construct(RoleAssetMapAdmin $model)
    {
        parent::__construct($model);
    }
}