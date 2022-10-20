<?php

namespace DaydreamLab\User\Controllers;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Services\BaseService;

abstract class UserController extends BaseController
{
    protected $package = 'User';

    public function __construct(BaseService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }
}
