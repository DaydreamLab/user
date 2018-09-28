<?php

namespace DaydreamLab\User\Controllers\Password\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Password\Admin\PasswordResetAdminService;
use DaydreamLab\User\Requests\Password\Admin\PasswordResetAdminRemovePost;
use DaydreamLab\User\Requests\Password\Admin\PasswordResetAdminStorePost;
use DaydreamLab\User\Requests\Password\Admin\PasswordResetAdminStatePost;
use DaydreamLab\User\Requests\Password\Admin\PasswordResetAdminSearchPost;

class PasswordResetAdminController extends BaseController
{
    public function __construct(PasswordResetAdminService $service)
    {
        parent::__construct($service);
    }

    public function getItem($id)
    {
        $this->service->getItem($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getItems()
    {
        $this->service->search(new Collection());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function remove(PasswordResetAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(PasswordResetAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(PasswordResetAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(PasswordResetAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
