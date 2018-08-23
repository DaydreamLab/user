<?php

namespace DaydreamLab\User\Repositories\Role\Front;

use DaydreamLab\User\Repositories\Role\RoleRepository;
use DaydreamLab\User\Models\Role\Front\RoleFront;

class RoleFrontRepository extends RoleRepository
{
    public function __construct(RoleFront $model)
    {
        parent::__construct($model);
    }
}