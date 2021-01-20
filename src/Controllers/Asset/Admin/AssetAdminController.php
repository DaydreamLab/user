<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\InputHelper;
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
        $this->service = $service;
    }


    public function getItem($id)
    {
        $this->service->canAction('getAsset');
        $this->service->getItem($id);

        return ResponseHelper:: response($this->service->status, $this->service->response);
    }


    public function getItems()
    {
        $this->service->search(new Collection());

        return $this->response($this->service->status, $this->service->response);
    }


    public function treeList()
    {
        $this->service->canAction('searchAsset');
        $this->service->treeList();

        return $this->response($this->service->status, $this->service->response);
    }


    public function ordering(AssetAdminOrderingPost $request)
    {
        $this->service->canAction('editAsset');
        $this->service->orderingNested($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(AssetAdminRemovePost $request)
    {
        $this->service->canAction('deleteAsset');
        $this->service->remove($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetAdminStatePost $request)
    {
        $this->service->canAction('updateAssetState');
        $this->service->state($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetAdminStorePost $request)
    {
        InputHelper::null($request->rulesInput(), 'id') ? $this->service->canAction('addAsset')
            : $this->service->canAction('editAsset');
        $this->service->store($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(AssetAdminSearchPost $request)
    {
        $this->service->canAction('searchAsset');
        $this->service->search($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }
}
