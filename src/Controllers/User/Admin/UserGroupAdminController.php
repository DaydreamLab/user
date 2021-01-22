<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
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


    public function getItem($id)
    {
        $this->service->canAction('getUserGroup');
        $this->service->getItem($id);

        return $this->response($this->service->status, $this->service->response);
    }


    public function getPage($group_id)
    {
        $this->service->getPage($group_id);

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(UserGroupAdminRemovePost $request)
    {
        $this->service->canAction('deleteUserGroup');
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(UserGroupAdminStatePost $request)
    {
        $this->service->canAction('updateUserGroupState');
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserGroupAdminStorePost $request)
    {
        InputHelper::null($request->validated(), 'id') ? $this->service->canAction('addUserGroup')
            : $this->service->canAction('editUserGroup');
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(UserGroupAdminSearchPost $request)
    {
        $this->service->canAction('searchUserGroup');
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function tree()
    {
        $this->service->canAction('getUserGroupTree');
        $this->service->tree();

        return $this->response($this->service->status, $this->service->response);
    }

}
