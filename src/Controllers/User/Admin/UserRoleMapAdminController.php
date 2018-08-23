<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\User\Admin\UserRoleMapAdminService;
use DaydreamLab\User\Requests\User\Admin\UserRoleMapAdminRemovePost;
use DaydreamLab\User\Requests\User\Admin\UserRoleMapAdminStorePost;
use DaydreamLab\User\Requests\User\Admin\UserRoleMapAdminStatePost;
use DaydreamLab\User\Requests\User\Admin\UserRoleMapAdminSearchPost;

class UserRoleMapAdminController extends BaseController
{
    public function __construct(UserRoleMapAdminService $service)
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


    public function remove(UserRoleMapAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(UserRoleMapAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(UserRoleMapAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(UserRoleMapAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
