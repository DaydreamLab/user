<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\Admin\AssetAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminSearchPost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminOrderingPost;

class AssetAdminController extends BaseController
{
    public function __construct(AssetAdminService $service)
    {
        parent::__construct($service);
    }


    public function getApis($id)
    {
        $this->service->getApis($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getGroups($id)
    {
        $this->service->getGroups($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
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


    public function ordering(AssetAdminOrderingPost $request)
    {
        $this->service->orderingNested($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function remove(AssetAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(AssetAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(AssetAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(AssetAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
