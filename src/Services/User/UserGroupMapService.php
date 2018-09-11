<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Repositories\User\UserGroupMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class UserGroupMapService extends BaseService
{
    protected $type = 'UserGroupMap';

    public function __construct(UserGroupMapRepository $repo)
    {
        parent::__construct($repo);
    }
}
