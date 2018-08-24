<?php

namespace DaydreamLab\User\Services\Role\Front;

use DaydreamLab\User\Repositories\Role\Front\RoleAssetApiMapFrontRepository;
use DaydreamLab\User\Services\Role\RoleAssetApiMapService;

class RoleAssetApiMapFrontService extends RoleAssetApiMapService
{
    protected $type = 'RoleAssetApiMapFront';

    public function __construct(RoleAssetApiMapFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
