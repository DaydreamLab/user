<?php

namespace DaydreamLab\User\Controllers\Asset\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\Front\AssetFrontService;
use DaydreamLab\User\Requests\Asset\Front\AssetFrontRemovePost;
use DaydreamLab\User\Requests\Asset\Front\AssetFrontStorePost;
use DaydreamLab\User\Requests\Asset\Front\AssetFrontStatePost;
use DaydreamLab\User\Requests\Asset\Front\AssetFrontSearchPost;
use DaydreamLab\User\Requests\Asset\Front\AssetFrontOrderingPost;


class AssetFrontController extends BaseController
{
    public function __construct(AssetFrontService $service)
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


    public function ordering(AssetFrontOrderingPost $request)
    {
        $this->service->ordering($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }

    public function remove(AssetFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(AssetFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(AssetFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(AssetFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
