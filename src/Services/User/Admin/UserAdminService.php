<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;

class UserAdminService extends UserService
{
    protected $type = 'UserAdmin';

    public function __construct(UserAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
