<?php

namespace DaydreamLab\User\Controllers\Asset;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\Asset\AssetApiService;

class AssetApiController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'AssetApi';

    protected $modelType = 'Base';

    public function __construct(AssetApiService $service)
    {
        parent::__construct($service);
    }
}
