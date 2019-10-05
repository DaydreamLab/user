<?php

namespace DaydreamLab\User\Controllers\User;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\User\UserService;
use DaydreamLab\User\Requests\User\UserRemovePost;
use DaydreamLab\User\Requests\User\UserStorePost;
use DaydreamLab\User\Requests\User\UserStatePost;
use DaydreamLab\User\Requests\User\UserSearchPost;

class UserController extends BaseController
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function logout()
    {
        $this->service->logout();
        return ResponseHelper::response($this->service->status, $this->service->response);
    }

}
