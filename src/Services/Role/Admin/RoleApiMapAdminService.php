<?php

namespace DaydreamLab\User\Services\Role\Admin;

use DaydreamLab\User\Repositories\Role\Admin\RoleApiMapAdminRepository;
use DaydreamLab\User\Services\Role\RoleApiMapService;

class RoleApiMapAdminService extends RoleApiMapService
{
    protected $type = 'RoleApiMapAdmin';

    public function __construct(RoleApiMapAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
