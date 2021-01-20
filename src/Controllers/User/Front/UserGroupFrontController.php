<?php

namespace DaydreamLab\User\Controllers\User\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\User\Front\UserGroupFrontService;
use DaydreamLab\User\Requests\User\Front\UserGroupFrontRemovePost;
use DaydreamLab\User\Requests\User\Front\UserGroupFrontStorePost;
use DaydreamLab\User\Requests\User\Front\UserGroupFrontStatePost;
use DaydreamLab\User\Requests\User\Front\UserGroupFrontSearchPost;

class UserGroupFrontController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'UserGroup';

    protected $modelType = 'Front';

    public function __construct(UserGroupFrontService $service)
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


    public function remove(UserGroupFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(UserGroupFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserGroupFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(UserGroupFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }
}
