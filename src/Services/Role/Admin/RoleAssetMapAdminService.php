<?php

namespace App\Services\Role\Admin;

use App\Repositories\Role\Admin\RoleAssetMapAdminRepository;
use App\Services\Role\RoleAssetMapService;

class RoleAssetMapAdminService extends RoleAssetMapService
{
    protected $type = 'RoleAssetMapAdmin';

    public function __construct(RoleAssetMapAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
