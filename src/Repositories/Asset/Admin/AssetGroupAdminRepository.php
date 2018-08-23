<?php

namespace DaydreamLab\User\Repositories\Asset\Admin;

use DaydreamLab\User\Repositories\Asset\AssetGroupRepository;
use DaydreamLab\User\Models\Asset\Admin\AssetGroupAdmin;

class AssetGroupAdminRepository extends AssetGroupRepository
{
    public function __construct(AssetGroupAdmin $model)
    {
        parent::__construct($model);
    }
}