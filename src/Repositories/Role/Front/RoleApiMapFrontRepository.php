<?php

namespace DaydreamLab\User\Repositories\Role\Front;

use DaydreamLab\User\Repositories\Role\RoleApiMapRepository;
use DaydreamLab\User\Models\Role\Front\RoleApiMapFront;

class RoleApiMapFrontRepository extends RoleApiMapRepository
{
    public function __construct(RoleApiMapFront $model)
    {
        parent::__construct($model);
    }
}