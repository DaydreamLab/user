<?php

namespace App\Repositories\Role;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use App\Models\Role\RoleAssetApiMap;

class RoleAssetApiMapRepository extends BaseRepository
{
    public function __construct(RoleAssetApiMap $model)
    {
        parent::__construct($model);
    }
}