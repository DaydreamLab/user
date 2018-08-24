<?php

namespace DaydreamLab\User\Repositories\Role\Front;

use DaydreamLab\User\Repositories\Role\RoleAssetApiMapRepository;
use DaydreamLab\User\Models\Role\Front\RoleAssetApiMapFront;

class RoleAssetApiMapFrontRepository extends RoleAssetApiMapRepository
{
    public function __construct(RoleAssetApiMapFront $model)
    {
        parent::__construct($model);
    }
}