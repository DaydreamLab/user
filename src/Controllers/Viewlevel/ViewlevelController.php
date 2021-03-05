<?php

namespace DaydreamLab\User\Controllers\Viewlevel;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\Viewlevel\ViewlevelService;

class ViewlevelController extends BaseController
{
    protected $package = 'User';

    protected $modelType = 'Base';

    public function __construct(ViewlevelService $service)
    {
        parent::__construct($service);
    }
}
