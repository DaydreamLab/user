<?php

namespace DaydreamLab\User\Repositories\User;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\JJAJ\Traits\NestedRepositoryTrait;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Support\Collection;

class UserGroupRepository extends BaseRepository
{
    use NestedRepositoryTrait;

    public function __construct(UserGroup $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    public function getTree()
    {
        $roles = $this->search(new Collection());
        return $roles->toTree();
    }
}