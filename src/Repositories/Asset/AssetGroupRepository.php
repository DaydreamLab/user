<?php

namespace DaydreamLab\User\Repositories\Asset;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Asset\AssetGroup;

class AssetGroupRepository extends BaseRepository
{
    public function __construct(AssetGroup $model)
    {
        parent::__construct($model);
    }
}