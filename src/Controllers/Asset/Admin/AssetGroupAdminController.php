<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\Admin\AssetGroupAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminSearchPost;

class AssetGroupAdminController extends BaseController
{
    public function __construct(AssetGroupAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }

    public function getItem($id)
    {
        $this->service->canAction('getAssetGroup');
        $this->service->getItem($id);

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(AssetGroupAdminRemovePost $request)
    {
        $this->service->canAction('deleteAssetGroup');
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetGroupAdminStatePost $request)
    {
        $this->service->canAction('updateAssetGroupState');
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetGroupAdminStorePost $request)
    {
        InputHelper::null($request->validated(), 'id') ? $this->service->canAction('addAssetGroup')
            : $this->service->canAction('editAssetGroup');
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(AssetGroupAdminSearchPost $request)
    {
        $this->service->canAction('searchAssetGroup');
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }
}
