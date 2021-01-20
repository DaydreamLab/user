<?php

namespace DaydreamLab\User\Controllers\User;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\User\UserGroupService;
use DaydreamLab\User\Requests\User\UserGroupRemovePost;
use DaydreamLab\User\Requests\User\UserGroupStorePost;
use DaydreamLab\User\Requests\User\UserGroupStatePost;
use DaydreamLab\User\Requests\User\UserGroupSearchPost;

class UserGroupController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'UserGroup';

    protected $modelType = 'Base';

    public function __construct(UserGroupService $service)
    {
        parent::__construct($service);
    }


    public function getItem($id)
    {
        $this->service->getItem($id);

        return $this->response($this->service->status, $this->service->response);
    }


    public function getItems()
    {
        $this->service->search(new Collection());

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(UserGroupRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(UserGroupStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserGroupStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(UserGroupSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }
}
