<?php

namespace DaydreamLab\User\Services\Role\Front;

use DaydreamLab\User\Repositories\Role\Front\RoleAssetMapFrontRepository;
use DaydreamLab\User\Services\Role\RoleAssetMapService;

class RoleAssetMapFrontService extends RoleAssetMapService
{
    protected $type = 'RoleAssetMapFront';

    public function __construct(RoleAssetMapFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
