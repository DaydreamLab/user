<?php

namespace DaydreamLab\User\Controllers\User\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\User\Front\UserRoleMapFrontService;
use DaydreamLab\User\Requests\User\Front\UserRoleMapFrontRemovePost;
use DaydreamLab\User\Requests\User\Front\UserRoleMapFrontStorePost;
use DaydreamLab\User\Requests\User\Front\UserRoleMapFrontStatePost;
use DaydreamLab\User\Requests\User\Front\UserRoleMapFrontSearchPost;


class UserRoleMapFrontController extends BaseController
{
    public function __construct(UserRoleMapFrontService $service)
    {
        parent::__construct($service);
    }


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


    public function remove(UserRoleMapFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(UserRoleMapFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(UserRoleMapFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(UserRoleMapFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
