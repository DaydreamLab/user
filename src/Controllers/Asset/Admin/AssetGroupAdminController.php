<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminGetItem;
use DaydreamLab\User\Services\Asset\Admin\AssetGroupAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminSearchPost;
use Illuminate\Http\Request;

class AssetGroupAdminController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'AssetGroup';

    protected $modelType = 'Admin';

    public function __construct(AssetGroupAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }

    public function getItem(AssetGroupAdminGetItem $request)
    {
        $this->service->getItem(collect(['id' => $request->route('id')]));

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(AssetGroupAdminRemovePost $request)
    {
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetGroupAdminStatePost $request)
    {
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetGroupAdminStorePost $request)
    {
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(AssetGroupAdminSearchPost $request)
    {
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }
}
