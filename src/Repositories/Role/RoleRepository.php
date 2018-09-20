<?php

namespace DaydreamLab\User\Repositories\Role;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\JJAJ\Traits\NestedRepositoryTrait;
use DaydreamLab\User\Models\Role\Role;
use Illuminate\Support\Collection;

class RoleRepository extends BaseRepository
{
    use NestedRepositoryTrait;

    public function __construct(Role $model)
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