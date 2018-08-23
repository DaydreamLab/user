<?php

namespace App\Repositories\Role\Front;

use App\Repositories\Role\RoleAssetMapRepository;
use App\Models\Role\Front\RoleAssetMapFront;

class RoleAssetMapFrontRepository extends RoleAssetMapRepository
{
    public function __construct(RoleAssetMapFront $model)
    {
        parent::__construct($model);
    }
}