<?php

namespace DaydreamLab\User\Repositories\User;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\User\UserRoleMap;

class UserRoleMapRepository extends BaseRepository
{
    public function __construct(UserRoleMap $model)
    {
        parent::__construct($model);
    }
}