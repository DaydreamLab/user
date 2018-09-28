<?php

namespace DaydreamLab\User\Controllers\Password;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Password\PasswordResetService;
use DaydreamLab\User\Requests\Password\PasswordResetRemovePost;
use DaydreamLab\User\Requests\Password\PasswordResetStorePost;
use DaydreamLab\User\Requests\Password\PasswordResetStatePost;
use DaydreamLab\User\Requests\Password\PasswordResetSearchPost;

class PasswordResetController extends BaseController
{
    public function __construct(PasswordResetService $service)
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


    public function remove(PasswordResetRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(PasswordResetStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(PasswordResetStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(PasswordResetSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
