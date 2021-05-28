<?php

namespace DaydreamLab\User\Repositories\User;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\User\UserGroup;

class UserGroupRepository extends BaseRepository
{
    public function __construct(UserGroup $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
