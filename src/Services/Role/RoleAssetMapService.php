<?php

namespace DaydreamLab\User\Services\Role;

use DaydreamLab\User\Repositories\Role\RoleAssetMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class RoleAssetMapService extends BaseService
{
    protected $type = 'RoleAssetMap';

    public function __construct(RoleAssetMapRepository $repo)
    {
        parent::__construct($repo);
    }
}
