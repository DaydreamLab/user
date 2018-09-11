<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Repositories\User\UserGroupRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class UserGroupService extends BaseService
{
    protected $type = 'UserGroup';

    public function __construct(UserGroupRepository $repo)
    {
        parent::__construct($repo);
    }
}
