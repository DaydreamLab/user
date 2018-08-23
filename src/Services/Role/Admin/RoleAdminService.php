<?php

namespace DaydreamLab\User\Services\Role\Admin;

use DaydreamLab\User\Repositories\Role\Admin\RoleAdminRepository;
use DaydreamLab\User\Services\Role\RoleService;

class RoleAdminService extends RoleService
{
    protected $type = 'RoleAdmin';

    public function __construct(RoleAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
