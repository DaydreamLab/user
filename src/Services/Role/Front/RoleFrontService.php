<?php

namespace DaydreamLab\User\Services\Role\Front;

use DaydreamLab\User\Repositories\Role\Front\RoleFrontRepository;
use DaydreamLab\User\Services\Role\RoleService;

class RoleFrontService extends RoleService
{
    protected $type = 'RoleFront';

    public function __construct(RoleFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
