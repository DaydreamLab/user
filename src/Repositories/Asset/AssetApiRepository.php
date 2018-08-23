<?php

namespace DaydreamLab\User\Repositories\Asset;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Asset\AssetApi;

class AssetApiRepository extends BaseRepository
{
    public function __construct(AssetApi $model)
    {
        parent::__construct($model);
    }
}