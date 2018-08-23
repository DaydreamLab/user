<?php

namespace DaydreamLab\User\Repositories\Asset\Front;

use DaydreamLab\User\Repositories\Asset\AssetGroupRepository;
use DaydreamLab\User\Models\Asset\Front\AssetGroupFront;

class AssetGroupFrontRepository extends AssetGroupRepository
{
    public function __construct(AssetGroupFront $model)
    {
        parent::__construct($model);
    }
}