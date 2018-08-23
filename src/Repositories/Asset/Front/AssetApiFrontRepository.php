<?php

namespace DaydreamLab\User\Repositories\Asset\Front;

use DaydreamLab\User\Repositories\Asset\AssetApiRepository;
use DaydreamLab\User\Models\Asset\Front\AssetApiFront;

class AssetApiFrontRepository extends AssetApiRepository
{
    public function __construct(AssetApiFront $model)
    {
        parent::__construct($model);
    }
}