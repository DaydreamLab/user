<?php

namespace DaydreamLab\User\Controllers\Api\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Requests\Api\Admin\ApiAdminGetItem;
use DaydreamLab\User\Resources\Api\Admin\Collections\ApiAdminListResourceCollection;
use DaydreamLab\User\Resources\Api\Admin\Models\ApiAdminResource;
use DaydreamLab\User\Services\Api\Admin\ApiAdminService;
use DaydreamLab\User\Requests\Api\Admin\ApiAdminRemovePost;
use DaydreamLab\User\Requests\Api\Admin\ApiAdminStorePost;
use DaydreamLab\User\Requests\Api\Admin\ApiAdminStatePost;
use DaydreamLab\User\Requests\Api\Admin\ApiAdminSearchPost;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class ApiAdminController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'Api';

    protected $modelType = 'Admin';

    public function __construct(ApiAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function getItem(ApiAdminGetItem $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response instanceof Api
            ? new ApiAdminResource($this->service->response)
            : $this->service->response
        );
    }


    public function remove(ApiAdminRemovePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(ApiAdminStatePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->state($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(ApiAdminStorePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response instanceof Api
            ? new ApiAdminResource($this->service->response)
            : $this->service->response
        );
    }


    public function search(ApiAdminSearchPost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response instanceof LengthAwarePaginator
            ? new ApiAdminListResourceCollection($this->service->response)
            : $this->service->response
        );
    }
}
