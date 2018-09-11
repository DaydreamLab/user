<?php

namespace DaydreamLab\User\Repositories\User;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\User\UserGroupMap;

class UserGroupMapRepository extends BaseRepository
{
    public function __construct(UserGroupMap $model)
    {
        parent::__construct($model);
    }
}