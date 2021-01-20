<?php

namespace DaydreamLab\User\Controllers\User;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\User\UserService;

class UserController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'User';

    protected $modelType = 'Base';

    public function __construct(UserService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function logout()
    {
        $this->service->logout();

        return $this->response($this->service->status, $this->service->response);
    }
}
