<?php

namespace DaydreamLab\User\Controllers\CompanyOrder\Admin;

use DaydreamLab\User\Controllers\UserController;
use DaydreamLab\User\Requests\CompanyOrder\Admin\CompanyOrderAdminGetItemRequest;
use DaydreamLab\User\Requests\CompanyOrder\Admin\CompanyOrderAdminRemoveRequest;
use DaydreamLab\User\Requests\CompanyOrder\Admin\CompanyOrderAdminSearchRequest;
use DaydreamLab\User\Requests\CompanyOrder\Admin\CompanyOrderAdminStoreRequest;
use DaydreamLab\User\Services\CompanyOrder\Admin\CompanyOrderAdminService;
use Throwable;

class CompanyOrderAdminController extends UserController
{
    protected $modelName = 'CompanyOrder';

    public function __construct(CompanyOrderAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function getItem(CompanyOrderAdminGetItemRequest $request)
    {
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(CompanyOrderAdminRemoveRequest $request)
    {
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(CompanyOrderAdminSearchRequest $request)
    {
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(CompanyOrderAdminStoreRequest $request)
    {
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }
}
