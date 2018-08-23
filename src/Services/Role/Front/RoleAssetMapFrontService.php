<?php

namespace App\Services\Role\Front;

use App\Repositories\Role\Front\RoleAssetMapFrontRepository;
use App\Services\Role\RoleAssetMapService;

class RoleAssetMapFrontService extends RoleAssetMapService
{
    protected $type = 'RoleAssetMapFront';

    public function __construct(RoleAssetMapFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
