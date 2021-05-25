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
use Illuminate\Http\Resources\Json\JsonResource;
use Throwable;

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
        try {
            $this->service->apply($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }


        return $this->response($this->service->status, $this->service->response);
    }



    public function getItem(UserTagAdminGetItem $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], UserTagAdminResource::class);
    }


    public function remove(UserTagAdminRemovePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserTagAdminStorePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], UserTagAdminResource::class);
    }


    public function search(UserTagAdminSearchPost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], UserTagAdminListResourceCollection::class);
    }
}
