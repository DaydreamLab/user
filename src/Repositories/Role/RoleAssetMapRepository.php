<?php

namespace App\Repositories\Role;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use App\Models\Role\RoleAssetMap;

class RoleAssetMapRepository extends BaseRepository
{
    public function __construct(RoleAssetMap $model)
    {
        parent::__construct($model);
    }
}