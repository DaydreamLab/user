<?php

namespace DaydreamLab\User\Controllers\Viewlevel;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Viewlevel\ViewlevelService;
use DaydreamLab\User\Requests\Viewlevel\ViewlevelRemovePost;
use DaydreamLab\User\Requests\Viewlevel\ViewlevelStorePost;
use DaydreamLab\User\Requests\Viewlevel\ViewlevelStatePost;
use DaydreamLab\User\Requests\Viewlevel\ViewlevelSearchPost;

class ViewlevelController extends BaseController
{
    public function __construct(ViewlevelService $service)
    {
        parent::__construct($service);
    }


    public function getItem($id)
    {
        $this->service->getItem($id);

        return $this->response($this->service->status, $this->service->response);
    }


    public function getItems()
    {
        $this->service->search(new Collection());

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(ViewlevelRemovePost $request)
    {
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(ViewlevelStatePost $request)
    {
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(ViewlevelStorePost $request)
    {
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(ViewlevelSearchPost $request)
    {
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }
}
