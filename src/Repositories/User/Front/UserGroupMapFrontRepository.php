<?php

namespace DaydreamLab\User\Repositories\User\Front;

use DaydreamLab\User\Repositories\User\UserGroupMapRepository;
use DaydreamLab\User\Models\User\Front\UserGroupMapFront;

class UserGroupMapFrontRepository extends UserGroupMapRepository
{
    public function __construct(UserGroupMapFront $model)
    {
        parent::__construct($model);
    }
}