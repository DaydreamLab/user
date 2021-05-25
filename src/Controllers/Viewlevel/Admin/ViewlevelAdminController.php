<?php

namespace DaydreamLab\User\Controllers\Viewlevel\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminGetItem;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminOrderingPost;
use DaydreamLab\User\Resources\Viewlevel\Admin\Collections\ViewlevelAdminListResourceCollection;
use DaydreamLab\User\Resources\Viewlevel\Admin\Models\ViewlevelAdminResource;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminRemovePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminStorePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminSearchPost;
use Throwable;

class ViewlevelAdminController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'Viewlevel';

    protected $modelType = 'Admin';

    public function __construct(ViewlevelAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function getItem(ViewlevelAdminGetItem $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], ViewlevelAdminResource::class);
    }


    public function ordering(ViewlevelAdminOrderingPost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->ordering($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(ViewlevelAdminRemovePost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(ViewlevelAdminStorePost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], ViewlevelAdminResource::class);
    }


    public function search(ViewlevelAdminSearchPost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], ViewlevelAdminListResourceCollection::class);
    }
}
