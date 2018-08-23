<?php

namespace DaydreamLab\User\Repositories\Asset\Admin;

use DaydreamLab\User\Repositories\Asset\AssetApiRepository;
use DaydreamLab\User\Models\Asset\Admin\AssetApiAdmin;

class AssetApiAdminRepository extends AssetApiRepository
{
    public function __construct(AssetApiAdmin $model)
    {
        parent::__construct($model);
    }
}