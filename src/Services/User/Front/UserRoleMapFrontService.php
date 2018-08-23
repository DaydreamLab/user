<?php

namespace DaydreamLab\User\Services\User\Front;

use DaydreamLab\User\Repositories\User\Front\UserRoleMapFrontRepository;
use DaydreamLab\User\Services\User\UserRoleMapService;

class UserRoleMapFrontService extends UserRoleMapService
{
    protected $type = 'UserRoleMapFront';

    public function __construct(UserRoleMapFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
