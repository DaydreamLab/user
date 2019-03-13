<?php

namespace DaydreamLab\User\Controllers\User;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Requests\User\UserGroupAssetMapStorePost;
use DaydreamLab\User\Requests\User\UserGroupMapStorePost;
use DaydreamLab\User\Services\User\UserGroupAssetMapService;
use DaydreamLab\User\Services\User\UserGroupMapService;

class UserGroupAssetMapController extends BaseController
{
    public function __construct(UserGroupAssetMapService $service)
    {
        parent::__construct($service);
        $this->service = $service;
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


    public function store(UserGroupAssetMapStorePost $request)
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
