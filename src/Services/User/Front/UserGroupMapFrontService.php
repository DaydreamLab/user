<?php

namespace DaydreamLab\User\Services\User\Front;

use DaydreamLab\User\Repositories\User\Front\UserGroupMapFrontRepository;
use DaydreamLab\User\Services\User\UserGroupMapService;

class UserGroupMapFrontService extends UserGroupMapService
{
    protected $type = 'UserGroupMapFront';

    public function __construct(UserGroupMapFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
