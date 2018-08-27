<?php

namespace DaydreamLab\User\Repositories\Asset\Admin;

use DaydreamLab\User\Repositories\Asset\AssetApiMapRepository;
use DaydreamLab\User\Models\Asset\Admin\AssetApiMapAdmin;

class AssetApiMapAdminRepository extends AssetApiMapRepository
{
    public function __construct(AssetApiMapAdmin $model)
    {
        parent::__construct($model);
    }
}