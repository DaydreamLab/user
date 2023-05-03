<?php

namespace DaydreamLab\User\Controllers\UserTagCategory\Admin;

use DaydreamLab\Cms\Resources\Category\Admin\Collections\CategoryAdminListResourceCollection;
use DaydreamLab\Cms\Resources\Category\Admin\Models\CategoryAdminResource;
use DaydreamLab\JJAJ\Traits\ApiJsonResponse;
use DaydreamLab\User\Requests\UserTagCategory\Admin\UserTagCategoryAdminGetItemRequest;
use DaydreamLab\User\Requests\UserTagCategory\Admin\UserTagCategoryAdminSearchRequest;
use DaydreamLab\User\Requests\UserTagCategory\Admin\UserTagCategoryAdminStatePost;
use DaydreamLab\User\Requests\UserTagCategory\Admin\UserTagCategoryAdminStoreRequest;
use DaydreamLab\User\Resources\UserTag\Admin\Collections\UserTagAdminSearchResourceCollection;
use DaydreamLab\User\Services\UserTagCategory\UserTagCategoryService;
use Throwable;

class UserTagCategoryAdminController
{
    use ApiJsonResponse;

    protected $modelName = 'UserTagCategory';

    protected $service;

    public function __construct(UserTagCategoryService $service)
    {
        $this->service = $service;
    }


    public function getItem(UserTagCategoryAdminGetItemRequest $request)
    {
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], CategoryAdminResource::class);
    }


    public function state(UserTagCategoryAdminStatePost $request)
    {
        try {
            $this->service->state($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(UserTagCategoryAdminSearchRequest $request)
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
            CategoryAdminListResourceCollection::class
        );
    }


    public function store(UserTagCategoryAdminStoreRequest $request)
    {
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response(
            $this->service->status,
            $this->service->response,
            [],
            CategoryAdminResource::class
        );
    }
}
