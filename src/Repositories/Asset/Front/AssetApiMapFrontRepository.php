<?php

namespace DaydreamLab\User\Repositories\Asset\Front;

use DaydreamLab\User\Repositories\Asset\AssetApiMapRepository;
use DaydreamLab\User\Models\Asset\Front\AssetApiMapFront;

class AssetApiMapFrontRepository extends AssetApiMapRepository
{
    public function __construct(AssetApiMapFront $model)
    {
        parent::__construct($model);
    }
}