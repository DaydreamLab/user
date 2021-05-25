<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminGetItem;
use DaydreamLab\User\Resources\Asset\Admin\Collections\AssetAdminListResourceCollection;
use DaydreamLab\User\Resources\Asset\Admin\Models\AssetAdminResource;
use DaydreamLab\User\Services\Asset\Admin\AssetAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminSearchPost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminOrderingPost;
use Throwable;

class AssetAdminController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'Asset';

    protected $modelType = 'Admin';

    public function __construct(AssetAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function getItem(AssetAdminGetItem $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], AssetAdminResource::class);
    }

    public function treeList()
    {
        try {
            $this->service->treeList();
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function ordering(AssetAdminOrderingPost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->ordering($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(AssetAdminRemovePost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->removeNested($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetAdminStatePost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->state($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetAdminStorePost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->storeNested($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], AssetAdminResource::class);
    }


    public function search(AssetAdminSearchPost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], AssetAdminListResourceCollection::class);
    }
}
