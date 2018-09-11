<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\User\Repositories\User\Admin\UserGroupAdminRepository;
use DaydreamLab\User\Services\User\UserGroupService;

class UserGroupAdminService extends UserGroupService
{
    protected $type = 'UserGroupAdmin';

    public function __construct(UserGroupAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
