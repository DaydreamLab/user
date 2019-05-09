<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\User\Repositories\Asset\Admin\AssetApiAdminRepository;
use DaydreamLab\User\Services\Asset\AssetApiService;

class AssetApiAdminService extends AssetApiService
{
    protected $type = 'AssetApiAdmin';

    protected $assetApiMapAdminService;

    public function __construct(AssetApiAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
