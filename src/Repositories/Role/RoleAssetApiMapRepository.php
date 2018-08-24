<?php

namespace DaydreamLab\User\Repositories\Role;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Role\RoleAssetApiMap;

class RoleAssetApiMapRepository extends BaseRepository
{
    public function __construct(RoleAssetApiMap $model)
    {
        parent::__construct($model);
    }
}