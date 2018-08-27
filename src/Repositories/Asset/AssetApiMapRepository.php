<?php

namespace DaydreamLab\User\Repositories\Asset;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Asset\AssetApiMap;

class AssetApiMapRepository extends BaseRepository
{
    public function __construct(AssetApiMap $model)
    {
        parent::__construct($model);
    }
}