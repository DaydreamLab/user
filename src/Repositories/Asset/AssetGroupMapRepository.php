<?php

namespace DaydreamLab\User\Repositories\Asset;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Asset\AssetGroupMap;

class AssetGroupMapRepository extends BaseRepository
{
    public function __construct(AssetGroupMap $model)
    {
        parent::__construct($model);
    }
}