<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\User\Repositories\User\Admin\UserRoleMapAdminRepository;
use DaydreamLab\User\Services\User\UserRoleMapService;

class UserRoleMapAdminService extends UserRoleMapService
{
    protected $type = 'UserRoleMapAdmin';

    public function __construct(UserRoleMapAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
