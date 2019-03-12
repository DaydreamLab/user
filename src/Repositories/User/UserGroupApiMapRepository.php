<?php

namespace DaydreamLab\User\Repositories\User;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\User\UserGroupApiMap;

class UserGroupApiMapRepository extends BaseRepository
{
    public function __construct(UserGroupApiMap $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}