<?php

namespace DaydreamLab\User\Repositories\Asset;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Asset\Asset;

class AssetRepository extends BaseRepository
{
    public function __construct(Asset $model)
    {
        parent::__construct($model);
    }
}
