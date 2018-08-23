<?php

namespace App\Repositories\Role\Admin;

use App\Repositories\Role\RoleAssetMapRepository;
use App\Models\Role\Admin\RoleAssetMapAdmin;

class RoleAssetMapAdminRepository extends RoleAssetMapRepository
{
    public function __construct(RoleAssetMapAdmin $model)
    {
        parent::__construct($model);
    }
}