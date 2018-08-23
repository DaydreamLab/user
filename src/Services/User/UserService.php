<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class UserService extends BaseService
{
    protected $type = 'User';

    public function __construct(UserRepository $repo)
    {
        parent::__construct($repo);
    }
}
