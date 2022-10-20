<?php

namespace DaydreamLab\User\Controllers\CompanyOrderItem\Admin;

use DaydreamLab\User\Controllers\UserController;
use DaydreamLab\User\Requests\CompanyOrderItem\Admin\CompanyOrderItemAdminGetItemRequest;
use DaydreamLab\User\Requests\CompanyOrderItem\Admin\CompanyOrderItemAdminRemoveRequest;
use DaydreamLab\User\Requests\CompanyOrderItem\Admin\CompanyOrderItemAdminSearchRequest;
use DaydreamLab\User\Requests\CompanyOrderItem\Admin\CompanyOrderItemAdminStoreRequest;
use DaydreamLab\User\Services\CompanyOrderItem\Admin\CompanyOrderItemAdminService;
use Throwable;

class CompanyOrderItemAdminController extends UserController
{
    protected $modelName = 'CompanyOrderItem';

    public function __construct(CompanyOrderItemAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function getItem(CompanyOrderItemAdminGetItemRequest $request)
    {
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(CompanyOrderItemAdminRemoveRequest $request)
    {
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(CompanyOrderItemAdminSearchRequest $request)
    {
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(CompanyOrderItemAdminStoreRequest $request)
    {
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }
}
