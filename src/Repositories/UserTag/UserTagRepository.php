<?php

namespace DaydreamLab\User\Repositories\UserTag;

use DaydreamLab\User\Models\UserTag\UserTag;
use DaydreamLab\User\Repositories\UserRepository;

class UserTagRepository extends UserRepository
{
    protected $modelName = 'UserTag';

    public function __construct(UserTag $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
