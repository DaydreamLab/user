<?php

namespace DaydreamLab\User\Repositories\User\Admin;

use DaydreamLab\User\Repositories\User\UserGroupRepository;
use DaydreamLab\User\Models\User\Admin\UserGroupAdmin;

class UserGroupAdminRepository extends UserGroupRepository
{

    public function __construct(UserGroupAdmin $model)
    {
        parent::__construct($model);
    }


    public function getPage($group_id)
    {
        return $this->find($group_id)->assets->toTree();
    }
}