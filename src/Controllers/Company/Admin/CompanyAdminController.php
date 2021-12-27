<?php

namespace DaydreamLab\User\Controllers\Company\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Resources\Company\Admin\Collections\CompanyAdminListResourceCollection;
use DaydreamLab\User\Resources\Company\Admin\Models\CompanyAdminResource;
use DaydreamLab\User\Services\Company\Admin\CompanyAdminService;
use DaydreamLab\User\Requests\Company\Admin\CompanyAdminExportPost;
use DaydreamLab\User\Requests\Company\Admin\CompanyAdminRemovePost;
use DaydreamLab\User\Requests\Company\Admin\CompanyAdminRestorePost;
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


    public function export(CompanyAdminExportPost $request)
    {
        $this->service->setUser($request->user('api'));
        return $this->service->export($request->validated());
    }


    public function getItem(Request $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], CompanyAdminResource::class);
    }


    public function ordering(CompanyAdminOrderingPost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->ordering($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function restore(CompanyAdminRestorePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->restore($request->validated());
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
        $this->service->setUser($request->user('api'));
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], CompanyAdminResource::class);
    }


    public function search(CompanyAdminSearchPost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], CompanyAdminListResourceCollection::class);
    }

    public function importCompany(Request $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->importCompany($request);
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }
}
