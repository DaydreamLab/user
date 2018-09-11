<?php

namespace DaydreamLab\User\Repositories\User\Front;

use DaydreamLab\User\Repositories\User\UserGroupRepository;
use DaydreamLab\User\Models\User\Front\UserGroupFront;

class UserGroupFrontRepository extends UserGroupRepository
{
    public function __construct(UserGroupFront $model)
    {
        parent::__construct($model);
    }
}