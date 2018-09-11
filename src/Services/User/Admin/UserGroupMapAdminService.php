<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\User\Repositories\User\Admin\UserGroupMapAdminRepository;
use DaydreamLab\User\Services\User\UserGroupMapService;

class UserGroupMapAdminService extends UserGroupMapService
{
    protected $type = 'UserGroupMapAdmin';

    public function __construct(UserGroupMapAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
