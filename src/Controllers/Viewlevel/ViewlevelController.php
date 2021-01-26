<?php

namespace DaydreamLab\User\Controllers\Viewlevel;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\Viewlevel\ViewlevelService;

class ViewlevelController extends BaseController
{
    public function __construct(ViewlevelService $service)
    {
        parent::__construct($service);
    }
}
