<?php

namespace DaydreamLab\User\Services\Role\Admin;

use DaydreamLab\User\Repositories\Role\Admin\RoleAssetApiMapAdminRepository;
use DaydreamLab\User\Services\Role\RoleAssetApiMapService;

class RoleAssetApiMapAdminService extends RoleAssetApiMapService
{
    protected $type = 'RoleAssetApiMapAdmin';

    public function __construct(RoleAssetApiMapAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
