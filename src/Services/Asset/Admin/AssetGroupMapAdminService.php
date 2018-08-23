<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\User\Repositories\Asset\Admin\AssetGroupMapAdminRepository;
use DaydreamLab\User\Services\Asset\AssetGroupMapService;

class AssetGroupMapAdminService extends AssetGroupMapService
{
    protected $type = 'AssetGroupMapAdmin';

    public function __construct(AssetGroupMapAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
