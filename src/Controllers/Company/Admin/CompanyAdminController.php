<?php

namespace DaydreamLab\User\Controllers\Company\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Models\Company\Admin\CompanyAdmin;
use DaydreamLab\User\Resources\Company\Admin\Collections\CompanyAdminListResourceCollection;
use DaydreamLab\User\Resources\Company\Admin\Models\CompanyAdminResource;
use DaydreamLab\User\Services\Company\Admin\CompanyAdminService;
use DaydreamLab\User\Requests\Company\Admin\CompanyAdminRemovePost;
use DaydreamLab\User\Requests\Company\Admin\CompanyAdminStorePost;
use DaydreamLab\User\Requests\Company\Admin\CompanyAdminSearchPost;
use DaydreamLab\User\Requests\Company\Admin\CompanyAdminOrderingPost;
use Illuminate\Http\Request;
use Throwable;

class CompanyAdminController extends BaseController
{
    protected $modelName = 'Company';

    protected $modelType = 'Admin';

    public function __construct(CompanyAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function getItem(Request $request)
    {
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response instanceof CompanyAdmin
            ? new CompanyAdminResource($this->service->response)
            : $this->service->response
        );
    }


    public function ordering(CompanyAdminOrderingPost $request)
    {
        try {
            $this->service->ordering($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(CompanyAdminRemovePost $request)
    {
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(CompanyAdminStorePost $request)
    {
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response instanceof CompanyAdmin
            ? new CompanyAdminResource($this->service->response)
            : $this->service->response
        );
    }


    public function search(CompanyAdminSearchPost $request)
    {
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, new CompanyAdminListResourceCollection($this->service->response));
    }
}
