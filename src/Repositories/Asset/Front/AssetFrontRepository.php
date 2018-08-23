<?php

namespace DaydreamLab\User\Repositories\Asset\Front;

use DaydreamLab\User\Repositories\Asset\AssetRepository;
use DaydreamLab\User\Models\Asset\Front\AssetFront;

class AssetFrontRepository extends AssetRepository
{
    public function __construct(AssetFront $model)
    {
        parent::__construct($model);
    }
}