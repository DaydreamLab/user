<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Requests\User\Admin\UserAdminBlockPost;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use DaydreamLab\User\Requests\User\Admin\UserAdminRemovePost;
use DaydreamLab\User\Requests\User\Admin\UserAdminStorePost;
use DaydreamLab\User\Requests\User\Admin\UserAdminStatePost;
use DaydreamLab\User\Requests\User\Admin\UserAdminSearchPost;

class UserAdminController extends BaseController
{
    public function __construct(UserAdminService $service)
    {
        parent::__construct($service);
    }


    public function block(UserAdminBlockPost $request)
    {
        $this->service->block($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getApis()
    {
        $this->service->getApis();

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getGrant($id)
    {
        $this->service->getGrant($id);

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


    public function getSelfPage()
    {
        $this->service->getPage();

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getUserPage($id)
    {
        $this->service->getPage($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }

    public function remove(UserAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(UserAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(UserAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(UserAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
