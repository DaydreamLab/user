<?php

namespace App\Services\Role;

use App\Repositories\Role\RoleAssetMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class RoleAssetMapService extends BaseService
{
    protected $type = 'RoleAssetMap';

    public function __construct(RoleAssetMapRepository $repo)
    {
        parent::__construct($repo);
    }
}
