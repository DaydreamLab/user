<?php

namespace DaydreamLab\User\Controllers\Asset\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\Asset\Front\AssetApiFrontService;

class AssetApiFrontController extends BaseController
{
    protected $modelType = 'Front';

    public function __construct(AssetApiFrontService $service)
    {
        parent::__construct($service);
    }
}
