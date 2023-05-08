<?php

namespace DaydreamLab\User\Controllers\CompanyOrder\Admin;

use DaydreamLab\User\Controllers\UserController;
use DaydreamLab\User\Requests\CompanyOrder\Admin\CompanyOrderAdminSearchRequest;
use DaydreamLab\User\Resources\CompanyOrder\Admin\Collections\CompanyAdminOrderListResourceCollection;
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


    public function search(CompanyOrderAdminSearchRequest $request)
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
            CompanyAdminOrderListResourceCollection::class
        );
    }
}
