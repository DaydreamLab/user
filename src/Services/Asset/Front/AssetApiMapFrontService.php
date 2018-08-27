<?php

namespace DaydreamLab\User\Services\Asset\Front;

use DaydreamLab\User\Repositories\Asset\Front\AssetApiMapFrontRepository;
use DaydreamLab\User\Services\Asset\AssetApiMapService;

class AssetApiMapFrontService extends AssetApiMapService
{
    protected $type = 'AssetApiMapFront';

    public function __construct(AssetApiMapFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
