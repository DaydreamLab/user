<?php

namespace DaydreamLab\User\Controllers\Role;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Role\RoleService;
use DaydreamLab\User\Requests\Role\RoleRemovePost;
use DaydreamLab\User\Requests\Role\RoleStorePost;
use DaydreamLab\User\Requests\Role\RoleStatePost;
use DaydreamLab\User\Requests\Role\RoleSearchPost;
use DaydreamLab\User\Requests\Role\RoleOrderingPost;

class RoleController extends BaseController
{
    public function __construct(RoleService $service)
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


    public function ordering(RoleOrderingPost $request)
    {
        $this->service->orderingNested($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function remove(RoleRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(RoleStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(RoleStorePost $request)
    {
        $this->service->storeNested($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(RoleSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
