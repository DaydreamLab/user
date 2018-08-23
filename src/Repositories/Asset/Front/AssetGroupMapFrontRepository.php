<?php

namespace DaydreamLab\User\Repositories\Asset\Front;

use DaydreamLab\User\Repositories\Asset\AssetGroupMapRepository;
use DaydreamLab\User\Models\Asset\Front\AssetGroupMapFront;

class AssetGroupMapFrontRepository extends AssetGroupMapRepository
{
    public function __construct(AssetGroupMapFront $model)
    {
        parent::__construct($model);
    }
}