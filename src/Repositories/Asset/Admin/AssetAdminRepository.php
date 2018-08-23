<?php

namespace DaydreamLab\User\Repositories\Asset\Admin;

use DaydreamLab\User\Repositories\Asset\AssetRepository;
use DaydreamLab\User\Models\Asset\Admin\AssetAdmin;

class AssetAdminRepository extends AssetRepository
{
    public function __construct(AssetAdmin $model)
    {
        parent::__construct($model);
    }
}