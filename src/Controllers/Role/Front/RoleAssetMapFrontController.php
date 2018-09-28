<?php

namespace DaydreamLab\User\Controllers\Role\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Role\Front\RoleAssetMapFrontService;
use DaydreamLab\User\Requests\Role\Front\RoleAssetMapFrontRemovePost;
use DaydreamLab\User\Requests\Role\Front\RoleAssetMapFrontStorePost;
use DaydreamLab\User\Requests\Role\Front\RoleAssetMapFrontStatePost;
use DaydreamLab\User\Requests\Role\Front\RoleAssetMapFrontSearchPost;


class RoleAssetMapFrontController extends BaseController
{
    public function __construct(RoleAssetMapFrontService $service)
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


    public function remove(RoleAssetMapFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(RoleAssetMapFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(RoleAssetMapFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(RoleAssetMapFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
