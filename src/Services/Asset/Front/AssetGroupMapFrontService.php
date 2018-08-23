<?php

namespace DaydreamLab\User\Services\Asset\Front;

use DaydreamLab\User\Repositories\Asset\Front\AssetGroupMapFrontRepository;
use DaydreamLab\User\Services\Asset\AssetGroupMapService;

class AssetGroupMapFrontService extends AssetGroupMapService
{
    protected $type = 'AssetGroupMapFront';

    public function __construct(AssetGroupMapFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
