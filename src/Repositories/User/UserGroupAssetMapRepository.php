<?php

namespace DaydreamLab\User\Repositories\User;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\User\UserGroupAssetMap;

class UserGroupAssetMapRepository extends BaseRepository
{
    public function __construct(UserGroupAssetMap $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}