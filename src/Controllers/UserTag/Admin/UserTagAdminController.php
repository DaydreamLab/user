<?php

namespace DaydreamLab\User\Controllers\UserTag\Admin;

use DaydreamLab\User\Controllers\UserController;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminEditUsersRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminGetItemRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminGetUsersRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminSearchRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminStateRequest;
use DaydreamLab\User\Requests\UserTag\Admin\UserTagAdminStoreRequest;
use DaydreamLab\User\Resources\UserTag\Admin\Collections\UserTagAdminGetUsersResourceCollection;
use DaydreamLab\User\Resources\UserTag\Admin\Collections\UserTagAdminSearchResourceCollection;
use DaydreamLab\User\Services\UserTag\Admin\UserTagAdminService;
use Throwable;

class UserTagAdminController extends UserController
{
    protected $modelName = 'UserTag';

    public function __construct(UserTagAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
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

        return $this->response($this->service->status, $this->service->response);
    }


    public function getUsers(UserTagAdminGetUsersRequest $request)
    {
        try {
            $this->service->getUsers($request->validated());
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
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }
}
