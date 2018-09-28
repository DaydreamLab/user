<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Requests\AssetApiAdminStoreMapsPost;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\Admin\AssetApiAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiAdminSearchPost;

class AssetApiAdminController extends BaseController
{
    public function __construct(AssetApiAdminService $service)
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


    public function remove(AssetApiAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(AssetApiAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(AssetApiAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function storeMaps(AssetApiAdminStoreMapsPost $request)
    {
        $this->service->storeMaps($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(AssetApiAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
