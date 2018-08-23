<?php

namespace DaydreamLab\User\Services\Asset\Front;

use DaydreamLab\User\Repositories\Asset\Front\AssetFrontRepository;
use DaydreamLab\User\Services\Asset\AssetService;

class AssetFrontService extends AssetService
{
    protected $type = 'AssetFront';

    public function __construct(AssetFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
