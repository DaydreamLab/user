<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\Admin\AssetApiMapAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiMapAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiMapAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiMapAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiMapAdminSearchPost;

class AssetApiMapAdminController extends BaseController
{
    public function __construct(AssetApiMapAdminService $service)
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


    public function remove(AssetApiMapAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(AssetApiMapAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(AssetApiMapAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(AssetApiMapAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
