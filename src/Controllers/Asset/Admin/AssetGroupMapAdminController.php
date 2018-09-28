<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\Admin\AssetGroupMapAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupMapAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupMapAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupMapAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupMapAdminSearchPost;

class AssetGroupMapAdminController extends BaseController
{
    public function __construct(AssetGroupMapAdminService $service)
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


    public function remove(AssetGroupMapAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(AssetGroupMapAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(AssetGroupMapAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(AssetGroupMapAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
