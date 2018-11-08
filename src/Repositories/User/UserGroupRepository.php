<?php

namespace DaydreamLab\User\Repositories\User;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\JJAJ\Traits\NestedRepositoryTrait;
use DaydreamLab\User\Models\User\UserGroup;

class UserGroupRepository extends BaseRepository
{
    use NestedRepositoryTrait;

    public function __construct(UserGroup $model)
    {
        parent::__construct($model);
    }
}