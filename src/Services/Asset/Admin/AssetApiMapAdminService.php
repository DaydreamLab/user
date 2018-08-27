<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\User\Repositories\Asset\Admin\AssetApiMapAdminRepository;
use DaydreamLab\User\Services\Asset\AssetApiMapService;

class AssetApiMapAdminService extends AssetApiMapService
{
    protected $type = 'AssetApiMapAdmin';

    public function __construct(AssetApiMapAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
