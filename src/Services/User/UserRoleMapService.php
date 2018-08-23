<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Repositories\User\UserRoleMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class UserRoleMapService extends BaseService
{
    protected $type = 'UserRoleMap';

    public function __construct(UserRoleMapRepository $repo)
    {
        parent::__construct($repo);
    }
}
