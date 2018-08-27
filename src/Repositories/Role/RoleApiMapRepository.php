<?php

namespace DaydreamLab\User\Repositories\Role;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Role\RoleApiMap;

class RoleApiMapRepository extends BaseRepository
{
    public function __construct(RoleApiMap $model)
    {
        parent::__construct($model);
    }
}