<?php

namespace DaydreamLab\User\Controllers\Asset\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\Asset\Front\AssetFrontService;

class AssetFrontController extends BaseController
{
    protected $modelType = 'Front';

    public function __construct(AssetFrontService $service)
    {
        parent::__construct($service);
    }
}
