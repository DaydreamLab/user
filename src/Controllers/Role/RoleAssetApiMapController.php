<?php

namespace DaydreamLab\User\Controllers\Role;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Role\RoleAssetApiMapService;
use DaydreamLab\User\Requests\Role\RoleAssetApiMapRemovePost;
use DaydreamLab\User\Requests\Role\RoleAssetApiMapStorePost;
use DaydreamLab\User\Requests\Role\RoleAssetApiMapStatePost;
use DaydreamLab\User\Requests\Role\RoleAssetApiMapSearchPost;

class RoleAssetApiMapController extends BaseController
{
    public function __construct(RoleAssetApiMapService $service)
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


    public function remove(RoleAssetApiMapRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(RoleAssetApiMapStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(RoleAssetApiMapStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(RoleAssetApiMapSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
