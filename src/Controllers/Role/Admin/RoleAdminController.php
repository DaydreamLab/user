<?php

namespace DaydreamLab\User\Controllers\Role\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Role\Admin\RoleAdminService;
use DaydreamLab\User\Requests\Role\Admin\RoleAdminRemovePost;
use DaydreamLab\User\Requests\Role\Admin\RoleAdminStorePost;
use DaydreamLab\User\Requests\Role\Admin\RoleAdminStatePost;
use DaydreamLab\User\Requests\Role\Admin\RoleAdminSearchPost;
use DaydreamLab\User\Requests\Role\Admin\RoleAdminOrderingPost;

class RoleAdminController extends BaseController
{
    public function __construct(RoleAdminService $service)
    {
        parent::__construct($service);
    }


    public function getAction($id)
    {
        $this->service->getAction($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getApis($id)
    {
        $this->service->getApis($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getApiIds($id)
    {
        $this->service->getApiIds($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
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


    public function getPage($id)
    {
        $this->service->getPage($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getTree()
    {
        $this->service->getTree();

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function ordering(RoleAdminOrderingPost $request)
    {
        $this->service->ordering($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function remove(RoleAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(RoleAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(RoleAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(RoleAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
