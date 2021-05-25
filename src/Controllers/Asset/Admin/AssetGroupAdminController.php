<?php

namespace DaydreamLab\User\Controllers\Asset\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Models\Asset\Admin\AssetGroupAdmin;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminOrderingPost;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminGetItem;
use DaydreamLab\User\Services\Asset\Admin\AssetGroupAdminService;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminRemovePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminStorePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminStatePost;
use DaydreamLab\User\Requests\Asset\Admin\AssetGroupAdminSearchPost;
use DaydreamLab\User\Resources\Asset\Admin\Collections\AssetGroupAdminListResourceCollection;
use DaydreamLab\User\Resources\Asset\Admin\Models\AssetGroupAdminResource;

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
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, new AssetGroupAdminResource($this->service->response));
    }


    public function ordering(AssetGroupAdminOrderingPost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->ordering($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(AssetGroupAdminRemovePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetGroupAdminStatePost $request)
    {
        $this->service->setUser($request->user('api'));

        try {
            $this->service->state($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetGroupAdminStorePost $request)
    {
        $this->service->setUser($request->user('api'));

        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status,
            $this->service->response instanceof AssetGroupAdmin
                ? new AssetGroupAdminResource($this->service->response)
                : $this->service->response
        );
    }


    public function search(AssetGroupAdminSearchPost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response instanceof LengthAwarePaginator
            ? new AssetGroupAdminListResourceCollection($this->service->response)
            : $this->service->response
        );
    }
}
