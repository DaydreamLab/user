<?php

namespace App\Repositories\Role\Front;

use App\Repositories\Role\RoleAssetApiMapRepository;
use App\Models\Role\Front\RoleAssetApiMapFront;

class RoleAssetApiMapFrontRepository extends RoleAssetApiMapRepository
{
    public function __construct(RoleAssetApiMapFront $model)
    {
        parent::__construct($model);
    }
}