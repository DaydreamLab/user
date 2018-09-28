<?php

namespace DaydreamLab\User\Controllers\Viewlevel\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Viewlevel\Front\ViewlevelFrontService;
use DaydreamLab\User\Requests\Viewlevel\Front\ViewlevelFrontRemovePost;
use DaydreamLab\User\Requests\Viewlevel\Front\ViewlevelFrontStorePost;
use DaydreamLab\User\Requests\Viewlevel\Front\ViewlevelFrontStatePost;
use DaydreamLab\User\Requests\Viewlevel\Front\ViewlevelFrontSearchPost;


class ViewlevelFrontController extends BaseController
{
    public function __construct(ViewlevelFrontService $service)
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


    public function remove(ViewlevelFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(ViewlevelFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(ViewlevelFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(ViewlevelFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
