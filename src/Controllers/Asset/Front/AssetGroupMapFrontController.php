<?php

namespace DaydreamLab\User\Controllers\Asset\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\Front\AssetGroupMapFrontService;
use DaydreamLab\User\Requests\Asset\Front\AssetGroupMapFrontRemovePost;
use DaydreamLab\User\Requests\Asset\Front\AssetGroupMapFrontStorePost;
use DaydreamLab\User\Requests\Asset\Front\AssetGroupMapFrontStatePost;
use DaydreamLab\User\Requests\Asset\Front\AssetGroupMapFrontSearchPost;


class AssetGroupMapFrontController extends BaseController
{
    public function __construct(AssetGroupMapFrontService $service)
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


    public function remove(AssetGroupMapFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(AssetGroupMapFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(AssetGroupMapFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(AssetGroupMapFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
