<?php

namespace DaydreamLab\User\Services\User\Front;

use DaydreamLab\User\Repositories\User\Front\UserFrontRepository;
use DaydreamLab\User\Services\User\UserService;

class UserFrontService extends UserService
{
    protected $type = 'UserFront';

    public function __construct(UserFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
