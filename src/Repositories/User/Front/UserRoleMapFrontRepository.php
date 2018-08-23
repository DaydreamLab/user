<?php

namespace DaydreamLab\User\Repositories\User\Front;

use DaydreamLab\User\Repositories\User\UserRoleMapRepository;
use DaydreamLab\User\Models\User\Front\UserRoleMapFront;

class UserRoleMapFrontRepository extends UserRoleMapRepository
{
    public function __construct(UserRoleMapFront $model)
    {
        parent::__construct($model);
    }
}