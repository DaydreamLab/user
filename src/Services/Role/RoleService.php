<?php

namespace DaydreamLab\User\Services\Role;

use DaydreamLab\User\Repositories\Role\RoleRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class RoleService extends BaseService
{
    protected $type = 'Role';

    public function __construct(RoleRepository $repo)
    {
        parent::__construct($repo);
    }
}
