<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
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
        $this->service = $service;
    }

    public function getItem($id)
    {
        $this->service->canAction('getApi');
        $this->service->getItem($id);

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(AssetApiAdminRemovePost $request)
    {
        $this->service->canAction('deleteApi');
        $this->service->remove($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetApiAdminStatePost $request)
    {
        $this->service->canAction('updateApiState');
        $this->service->state($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetApiAdminStorePost $request)
    {
        InputHelper::null($request->rulesInput(), 'id') ? $this->service->canAction('addApi')
            : $this->service->canAction('editApi');
        $this->service->store($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(AssetApiAdminSearchPost $request)
    {
        $this->service->canAction('searchApi');
        $this->service->search($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }
}
