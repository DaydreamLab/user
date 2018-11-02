<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\User\Admin\UserGroupAdminService;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminRemovePost;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminStorePost;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminStatePost;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminSearchPost;

class UserGroupAdminController extends BaseController
{
    public function __construct(UserGroupAdminService $service)
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


    public function remove(UserGroupAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(UserGroupAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(UserGroupAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(UserGroupAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function tree()
    {
        $this->service->tree();

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function treeList()
    {
        $this->service->treeList();

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
