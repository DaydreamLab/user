<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\User\Requests\User\Admin\UserAdminBlockPost;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use DaydreamLab\User\Requests\User\Admin\UserAdminRemovePost;
use DaydreamLab\User\Requests\User\Admin\UserAdminStorePost;
use DaydreamLab\User\Requests\User\Admin\UserAdminSearchPost;

class UserAdminController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'User';

    protected $modelType = 'Admin';

    public function __construct(UserAdminService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function block(UserAdminBlockPost $request)
    {
        $this->service->canAction('blockUser');
        $this->service->setUser($request->user);
        $this->service->block($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function getItem($id)
    {
        $this->service->canAction('getUser');
        $this->service->getItem($id);

        return $this->response($this->service->status, $this->service->response);
    }


    public function getSelfPage()
    {
        $this->service->canAction('getSelfPage');
        $this->service->getSelfPage();

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(UserAdminRemovePost $request)
    {
        $this->service->canAction('deleteUser');
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserAdminStorePost $request)
    {
        $input = $request->validated();

        if (!InputHelper::null($input, 'activation'))
        {
            $input->forget('activation');
            if($input->activation == 'false')
            {
                $input->put('activation', 0);
            }
            else
            {
                $input->put('activation', 1);
            }
            $this->service->canAction('editUser');
        }
        else
        {
            $this->service->canAction('addUser');
        }

        $this->service->store($input);

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(UserAdminSearchPost $request)
    {
        $this->service->canAction('searchUser');
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }
}
