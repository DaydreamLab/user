<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Requests\User\Admin\UserTagAdminApplyPost;
use DaydreamLab\User\Requests\User\Admin\UserTagAdminGetItem;
use DaydreamLab\User\Requests\User\Admin\UserTagAdminRemovePost;
use DaydreamLab\User\Requests\User\Admin\UserTagAdminSearchPost;
use DaydreamLab\User\Requests\User\Admin\UserTagAdminStorePost;
use DaydreamLab\User\Resources\User\Admin\Collections\UserTagAdminListResourceCollection;
use DaydreamLab\User\Resources\User\Admin\Models\UserTagAdminResource;
use DaydreamLab\User\Services\User\Admin\UserTagAdminService;

class UserTagAdminController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'UserTag';

    protected $modelType = 'Admin';

    public function __construct(UserTagAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function apply(UserTagAdminApplyPost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->apply($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }



    public function getItem(UserTagAdminGetItem $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->getItem(collect(['id' => $request->route('id')]));

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(UserTagAdminRemovePost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserTagAdminStorePost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->store($request->validated());

        return $this->response($this->service->status,
            gettype($this->service->response) == 'object'
            ? new UserTagAdminResource($this->service->response)
            : $this->service->response
        );
    }


    public function search(UserTagAdminSearchPost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->search($request->validated());

        return $this->response($this->service->status,
            new UserTagAdminListResourceCollection($this->service->response));
    }
}
