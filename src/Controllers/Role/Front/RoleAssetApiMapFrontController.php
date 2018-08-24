<?php

namespace DaydreamLab\User\Controllers\Role\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Role\Front\RoleAssetApiMapFrontService;
use DaydreamLab\User\Requests\Role\Front\RoleAssetApiMapFrontRemovePost;
use DaydreamLab\User\Requests\Role\Front\RoleAssetApiMapFrontStorePost;
use DaydreamLab\User\Requests\Role\Front\RoleAssetApiMapFrontStatePost;
use DaydreamLab\User\Requests\Role\Front\RoleAssetApiMapFrontSearchPost;


class RoleAssetApiMapFrontController extends BaseController
{
    public function __construct(RoleAssetApiMapFrontService $service)
    {
        parent::__construct($service);
    }


    public function getItem($id)
    {
        $this->service->find($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getItems()
    {
        $this->service->search(new Collection());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function remove(RoleAssetApiMapFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(RoleAssetApiMapFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(RoleAssetApiMapFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(RoleAssetApiMapFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
