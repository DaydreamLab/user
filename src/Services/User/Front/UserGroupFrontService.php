<?php

namespace DaydreamLab\User\Services\User\Front;

use DaydreamLab\User\Repositories\User\Front\UserGroupFrontRepository;
use DaydreamLab\User\Services\User\UserGroupService;

class UserGroupFrontService extends UserGroupService
{
    protected $type = 'UserGroupFront';

    public function __construct(UserGroupFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
