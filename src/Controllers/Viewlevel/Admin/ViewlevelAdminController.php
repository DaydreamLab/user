<?php

namespace DaydreamLab\User\Controllers\Viewlevel\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminRemovePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminStorePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminStatePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminSearchPost;

class ViewlevelAdminController extends BaseController
{
    public function __construct(ViewlevelAdminService $service)
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


    public function remove(ViewlevelAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(ViewlevelAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(ViewlevelAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(ViewlevelAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
