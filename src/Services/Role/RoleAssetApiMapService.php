<?php

namespace App\Services\Role;

use App\Repositories\Role\RoleAssetApiMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class RoleAssetApiMapService extends BaseService
{
    protected $type = 'RoleAssetApiMap';

    public function __construct(RoleAssetApiMapRepository $repo)
    {
        parent::__construct($repo);
    }
}
