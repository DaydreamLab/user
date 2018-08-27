<?php

namespace DaydreamLab\User\Services\Role\Front;

use DaydreamLab\User\Repositories\Role\Front\RoleApiMapFrontRepository;
use DaydreamLab\User\Services\Role\RoleApiMapService;

class RoleApiMapFrontService extends RoleApiMapService
{
    protected $type = 'RoleApiMapFront';

    public function __construct(RoleApiMapFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
