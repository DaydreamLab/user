<?php

namespace DaydreamLab\User\Controllers\Asset;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\AssetService;
use DaydreamLab\User\Requests\Asset\AssetRemovePost;
use DaydreamLab\User\Requests\Asset\AssetStorePost;
use DaydreamLab\User\Requests\Asset\AssetStatePost;
use DaydreamLab\User\Requests\Asset\AssetSearchPost;
use DaydreamLab\User\Requests\Asset\AssetOrderingPost;

class AssetController extends BaseController
{
    public function __construct(AssetService $service)
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


    public function ordering(AssetOrderingPost $request)
{
    $this->service->orderingNested($request->rulesInput());

    return ResponseHelper::response($this->service->status, $this->service->response);
}


    public function remove(AssetRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(AssetStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(AssetStorePost $request)
    {
        $this->service->storeNested($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(AssetSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
