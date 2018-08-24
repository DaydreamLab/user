<?php

namespace DaydreamLab\User\Repositories\Role\Admin;

use DaydreamLab\User\Repositories\Role\RoleAssetApiMapRepository;
use DaydreamLab\User\Models\Role\Admin\RoleAssetApiMapAdmin;

class RoleAssetApiMapAdminRepository extends RoleAssetApiMapRepository
{
    public function __construct(RoleAssetApiMapAdmin $model)
    {
        parent::__construct($model);
    }
}