<?php

namespace DaydreamLab\User\Controllers\User;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\User\UserTagService;

class UserTagController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'UserTag';

    protected $modelType = 'Base';

    public function __construct(UserTagService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }
}