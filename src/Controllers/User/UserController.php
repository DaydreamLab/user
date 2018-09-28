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
    }



    public function logout()
    {
        $this->service->logout();
        return ResponseHelper::response($this->service->status, $this->service->response);
    }
/*
    public function getItem($id)
    {
        $this->service->getItem($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getItems()
    {
        $this->service->search(new Collection());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function remove(UserRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(UserStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(UserStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(UserSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
*/
}
