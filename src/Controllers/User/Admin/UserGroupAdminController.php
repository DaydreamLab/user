<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Resources\User\Admin\Collections\UserGroupAdminListResourceCollection;
use DaydreamLab\User\Resources\User\Admin\Models\UserGroupAdminResource;
use Illuminate\Http\Request;
use DaydreamLab\User\Services\User\Admin\UserGroupAdminService;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminRemovePost;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminStorePost;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminStatePost;
use DaydreamLab\User\Requests\User\Admin\UserGroupAdminSearchPost;

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
        $this->service->getItem(collect(['id' => $request->route('id')]));

        return $this->response($this->service->status, new UserGroupAdminResource($this->service->response));
    }


    public function getPage(Request $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->getPage($request->route('id'));

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(UserGroupAdminRemovePost $request)
    {
        $this->service->setUser($request->user());
        $this->service->removeNested($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(UserGroupAdminStatePost $request)
    {
        $this->service->setUser($request->user());
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserGroupAdminStorePost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->store($request->validated());

        return $this->response($this->service->status,
            gettype($this->service->response) == 'object'
                ? new UserGroupAdminResource($this->service->response)
                : $this->service->response);
    }


    public function search(UserGroupAdminSearchPost $request)
    {
        $this->service->setUser($request->user());
        $this->service->search($request->validated());

        return $this->response($this->service->status,
            new UserGroupAdminListResourceCollection($this->service->response));
    }


    public function tree(Request $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->tree();

        return $this->response($this->service->status, $this->service->response);
    }
}
