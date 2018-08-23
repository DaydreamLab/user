<?php

namespace App\Services\Role\Admin;

use App\Repositories\Role\Admin\RoleAssetApiMapAdminRepository;
use App\Services\Role\RoleAssetApiMapService;

class RoleAssetApiMapAdminService extends RoleAssetApiMapService
{
    protected $type = 'RoleAssetApiMapAdmin';

    public function __construct(RoleAssetApiMapAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
