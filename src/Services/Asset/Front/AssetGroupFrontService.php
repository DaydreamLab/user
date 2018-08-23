<?php

namespace DaydreamLab\User\Services\Asset\Front;

use DaydreamLab\User\Repositories\Asset\Front\AssetGroupFrontRepository;
use DaydreamLab\User\Services\Asset\AssetGroupService;

class AssetGroupFrontService extends AssetGroupService
{
    protected $type = 'AssetGroupFront';

    public function __construct(AssetGroupFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
