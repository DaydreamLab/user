<?php

namespace DaydreamLab\User\Services\Asset\Front;

use DaydreamLab\User\Repositories\Asset\Front\AssetApiFrontRepository;
use DaydreamLab\User\Services\Asset\AssetApiService;

class AssetApiFrontService extends AssetApiService
{
    protected $type = 'AssetApiFront';

    public function __construct(AssetApiFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
