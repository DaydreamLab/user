<?php

namespace DaydreamLab\User\Controllers\Asset\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\Front\AssetGroupFrontService;
use DaydreamLab\User\Requests\Asset\Front\AssetGroupFrontRemovePost;
use DaydreamLab\User\Requests\Asset\Front\AssetGroupFrontStorePost;
use DaydreamLab\User\Requests\Asset\Front\AssetGroupFrontStatePost;
use DaydreamLab\User\Requests\Asset\Front\AssetGroupFrontSearchPost;


class AssetGroupFrontController extends BaseController
{
    protected $modelType = 'Front';

    public function __construct(AssetGroupFrontService $service)
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


    public function remove(AssetGroupFrontRemovePost $request)
    {
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetGroupFrontStatePost $request)
    {
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetGroupFrontStorePost $request)
    {
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(AssetGroupFrontSearchPost $request)
    {
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }
}
