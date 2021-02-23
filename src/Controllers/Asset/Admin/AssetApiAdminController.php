<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiAdminGetItem;
use DaydreamLab\User\Resources\Asset\Admin\Collections\AssetApiAdminListResourceCollection;
use DaydreamLab\User\Resources\Asset\Admin\Models\AssetApiAdminResource;
use DaydreamLab\User\Services\Asset\Admin\AssetApiAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetApiAdminSearchPost;

class AssetApiAdminController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'Api';

    protected $modelType = 'Admin';

    public function __construct(AssetApiAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }

    public function getItem(AssetApiAdminGetItem $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->getItem(collect(['id' => $request->route('id')]));

        return $this->response($this->service->status, new AssetApiAdminResource($this->service->response));
    }


    public function remove(AssetApiAdminRemovePost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetApiAdminStatePost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetApiAdminStorePost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->store($request->validated());

        return $this->response($this->service->status,
            gettype($this->service->response)
                ? new AssetApiAdminResource($this->service->response)
                : null
        );
    }


    public function search(AssetApiAdminSearchPost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->search($request->validated());

        return $this->response($this->service->status, new AssetApiAdminListResourceCollection($this->service->response));
    }
}
