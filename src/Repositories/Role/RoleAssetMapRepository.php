<?php

namespace DaydreamLab\User\Repositories\Role;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Role\RoleAssetMap;

class RoleAssetMapRepository extends BaseRepository
{
    public function __construct(RoleAssetMap $model)
    {
        parent::__construct($model);
    }
}