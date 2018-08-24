<?php

namespace DaydreamLab\User\Repositories\Role\Front;

use DaydreamLab\User\Repositories\Role\RoleAssetMapRepository;
use DaydreamLab\User\Models\Role\Front\RoleAssetMapFront;

class RoleAssetMapFrontRepository extends RoleAssetMapRepository
{
    public function __construct(RoleAssetMapFront $model)
    {
        parent::__construct($model);
    }
}