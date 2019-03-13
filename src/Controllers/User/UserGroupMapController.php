<?php

namespace DaydreamLab\User\Controllers\User;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Requests\User\UserGroupMapStorePost;
use DaydreamLab\User\Services\User\UserGroupMapService;

class UserGroupMapController extends BaseController
{
    public function __construct(UserGroupMapService $service)
    {
        parent::__construct($service);
    }


//    public function getItem($id)
//    {
//        $this->service->getItem($id);
//
//        return ResponseHelper::response($this->service->status, $this->service->response);
//    }
//
//
//    public function getItems()
//    {
//        $this->service->search(new Collection());
//
//        return ResponseHelper::response($this->service->status, $this->service->response);
//    }
//
//
//    public function remove(UserGroupRemovePost $request)
//    {
//        $this->service->remove($request->rulesInput());
//
//        return ResponseHelper::response($this->service->status, $this->service->response);
//    }
//
//
//    public function state(UserGroupStatePost $request)
//    {
//        $this->service->state($request->rulesInput());
//
//        return ResponseHelper::response($this->service->status, $this->service->response);
//    }


    public function store(UserGroupMapStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


//    public function search(UserGroupSearchPost $request)
//    {
//        $this->service->search($request->rulesInput());
//
//        return ResponseHelper::response($this->service->status, $this->service->response);
//    }
}
