<?php

namespace App\Services\Role\Front;

use App\Repositories\Role\Front\RoleAssetApiMapFrontRepository;
use App\Services\Role\RoleAssetApiMapService;

class RoleAssetApiMapFrontService extends RoleAssetApiMapService
{
    protected $type = 'RoleAssetApiMapFront';

    public function __construct(RoleAssetApiMapFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
