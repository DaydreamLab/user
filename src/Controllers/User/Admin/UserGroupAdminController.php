<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Resources\User\Admin\Collections\UserGroupAdminListResourceCollection;
use DaydreamLab\User\Resources\User\Admin\Models\UserGroupAdminResource;
use Illuminate\Http\Request;
use DaydreamLab\User\Services\User\Admin\UserGroupAdminService;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminRemovePost;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminStorePost;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminSearchPost;
use Throwable;

class UserGroupAdminController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'UserGroup';

    protected $modelType = 'Admin';


    public function __construct(UserGroupAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function getItem(Request $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, new UserGroupAdminResource($this->service->response));
    }


    public function getPage(Request $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getPage($request->route('id'));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(UserGroupAdminRemovePost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->removeNested($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserGroupAdminStorePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status,
            gettype($this->service->response) == 'object'
                ? new UserGroupAdminResource($this->service->response)
                : $this->service->response);
    }


    public function search(UserGroupAdminSearchPost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status,
            new UserGroupAdminListResourceCollection($this->service->response));
    }


    public function tree(Request $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->tree();
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }
}
