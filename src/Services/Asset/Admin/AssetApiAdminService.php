<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Asset\Admin\AssetApiAdminRepository;
use DaydreamLab\User\Services\Asset\AssetApiService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AssetApiAdminService extends AssetApiService
{
    protected $type = 'AssetApiAdmin';

    protected $assetApiMapAdminService;

    public function __construct(AssetApiAdminRepository $repo,
                                AssetApiMapAdminService $assetApiMapAdminService)
    {
        $this->assetApiMapAdminService = $assetApiMapAdminService;
        parent::__construct($repo);
    }
}
