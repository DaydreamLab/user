<?php

namespace DaydreamLab\User\Controllers\Role\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Role\Front\RoleFrontService;
use DaydreamLab\User\Requests\Role\Front\RoleFrontRemovePost;
use DaydreamLab\User\Requests\Role\Front\RoleFrontStorePost;
use DaydreamLab\User\Requests\Role\Front\RoleFrontStatePost;
use DaydreamLab\User\Requests\Role\Front\RoleFrontSearchPost;
use DaydreamLab\User\Requests\Role\Front\RoleFrontOrderingPost;


class RoleFrontController extends BaseController
{
    public function __construct(RoleFrontService $service)
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


    public function ordering(RoleFrontOrderingPost $request)
    {
        $this->service->ordering($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function remove(RoleFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(RoleFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(RoleFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(RoleFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
