<?php

namespace DaydreamLab\User\Controllers\Company\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\Company\Front\CompanyFrontService;
use DaydreamLab\User\Requests\Company\Front\CompanyFrontRemovePost;
use DaydreamLab\User\Requests\Company\Front\CompanyFrontStorePost;
use DaydreamLab\User\Requests\Company\Front\CompanyFrontSearchPost;
use DaydreamLab\User\Requests\Company\Front\CompanyFrontOrderingPost;

class CompanyFrontController extends BaseController
{
    protected $modelName = 'Company';

    protected $modelType = 'Front';

    public function __construct(CompanyFrontService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function getItem($id)
    {
        $this->service->getItem($id);

        return $this->response($this->service->status, $this->service->response);
    }


    public function ordering(CompanyFrontOrderingPost $request)
    {
        $this->service->ordering($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(CompanyFrontRemovePost $request)
    {
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(CompanyFrontStorePost $request)
    {
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(CompanyFrontSearchPost $request)
    {
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }
}
