<?php

namespace App\Repositories\Role\Admin;

use App\Repositories\Role\RoleAssetApiMapRepository;
use App\Models\Role\Admin\RoleAssetApiMapAdmin;

class RoleAssetApiMapAdminRepository extends RoleAssetApiMapRepository
{
    public function __construct(RoleAssetApiMapAdmin $model)
    {
        parent::__construct($model);
    }
}