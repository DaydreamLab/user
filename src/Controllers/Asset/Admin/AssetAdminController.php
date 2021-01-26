<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminGetItem;
use DaydreamLab\User\Resources\Asset\Admin\Collections\AssetAdminListResourceCollection;
use DaydreamLab\User\Resources\Asset\Admin\Models\AssetAdminResource;
use Illuminate\Http\Request;
use DaydreamLab\User\Services\Asset\Admin\AssetAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminSearchPost;
use DaydreamLab\User\Requests\Asset\Admin\AssetAdminOrderingPost;

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
        $this->service->getItem(collect(['id' => $request->route('id')]));

        return$this->response($this->service->status, new AssetAdminResource($this->service->response));
    }

    public function treeList()
    {
        $this->service->treeList();

        return $this->response($this->service->status, $this->service->response);
    }


    public function ordering(AssetAdminOrderingPost $request)
    {
        $this->service->setUser($request->user());
        $this->service->ordering($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(AssetAdminRemovePost $request)
    {
        $this->service->setUser($request->user());
        $this->service->removeNested($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetAdminStatePost $request)
    {
        $this->service->setUser($request->user());
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetAdminStorePost $request)
    {
        $this->service->setUser($request->user());
        $this->service->storeNested($request->validated());

        return $this->response($this->service->status,
            gettype($this->service->response)
            ? new AssetAdminResource($this->service->response)
            : null
        );
    }


    public function search(AssetAdminSearchPost $request)
    {
        $this->service->setUser($request->user());
        $this->service->search($request->validated());

        return $this->response($this->service->status, new AssetAdminListResourceCollection($this->service->response));
    }
}
