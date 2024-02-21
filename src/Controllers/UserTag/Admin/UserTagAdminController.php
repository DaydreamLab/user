<?php

namespace DaydreamLab\User\Controllers\UserTag\Admin;

use DaydreamLab\User\Controllers\UserController;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminBatchRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminRemovePost;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminEditUsersRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminGetItemRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminGetUsersRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminSearchRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminStateRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminStoreRequest;
use DaydreamLab\User\Resources\UserTag\Admin\Collections\UserTagAdminGetUsersResourceCollection;
use DaydreamLab\User\Resources\UserTag\Admin\Collections\UserTagAdminSearchResourceCollection;
use DaydreamLab\User\Resources\UserTag\Admin\Models\UserTagAdminResource;
use DaydreamLab\User\Services\UserTag\Admin\UserTagAdminService;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserTagAdminController extends UserController
{
    protected $modelName = 'UserTag';

    public function __construct(UserTagAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function batch(UserTagAdminBatchRequest $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->batch($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function editUsers(UserTagAdminEditUsersRequest $request)
    {
        try {
            $this->service->editUsers($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function getItem(UserTagAdminGetItemRequest $request)
    {
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], UserTagAdminResource::class);
    }


    public function getUsers(UserTagAdminGetUsersRequest $request)
    {
        try {
//            startLog();
            $this->service->getUsers($request->validated());
//            showLog();
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response(
            $this->service->status,
            $this->service->response,
            [],
            UserTagAdminGetUsersResourceCollection::class
        );
    }

    public function remove(UserTagAdminRemovePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(UserTagAdminSearchRequest $request)
    {
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response(
            $this->service->status,
            $this->service->response,
            [],
            UserTagAdminSearchResourceCollection::class
        );
    }


    public function state(UserTagAdminStateRequest $request)
    {
        try {
            $this->service->state($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserTagAdminStoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->service->store($request->validated());
            });
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response(
            $this->service->status,
            $this->service->response,
            [],
            UserTagAdminResource::class
        );
    }
}
