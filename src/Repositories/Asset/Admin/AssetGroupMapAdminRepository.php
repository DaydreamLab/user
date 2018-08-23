<?php

namespace DaydreamLab\User\Repositories\Asset\Admin;

use DaydreamLab\User\Repositories\Asset\AssetGroupMapRepository;
use DaydreamLab\User\Models\Asset\Admin\AssetGroupMapAdmin;

class AssetGroupMapAdminRepository extends AssetGroupMapRepository
{
    public function __construct(AssetGroupMapAdmin $model)
    {
        parent::__construct($model);
    }
}