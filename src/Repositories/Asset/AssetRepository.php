<?php

namespace DaydreamLab\User\Repositories\Asset;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\JJAJ\Traits\NestedRepositoryTrait;
use DaydreamLab\User\Models\Asset\Asset;

class AssetRepository extends BaseRepository
{
    use NestedRepositoryTrait;

    public function __construct(Asset $model)
    {
        parent::__construct($model);
    }
}