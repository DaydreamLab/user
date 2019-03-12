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
    }


    public function getPage($role_id)
    {
        return $this->find($role_id)->assets->toTree();
    }


    public function getTree()
    {
        $roles = $this->search(new Collection());
        return $roles->toTree();
    }
}