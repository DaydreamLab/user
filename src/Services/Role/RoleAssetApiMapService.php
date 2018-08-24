<?php

namespace DaydreamLab\User\Services\Role;

use DaydreamLab\User\Repositories\Role\RoleAssetApiMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class RoleAssetApiMapService extends BaseService
{
    protected $type = 'RoleAssetApiMap';

    public function __construct(RoleAssetApiMapRepository $repo)
    {
        parent::__construct($repo);
    }
}
