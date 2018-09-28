<?php

namespace DaydreamLab\User\Controllers\Password\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Password\Front\PasswordResetFrontService;
use DaydreamLab\User\Requests\Password\Front\PasswordResetFrontRemovePost;
use DaydreamLab\User\Requests\Password\Front\PasswordResetFrontStorePost;
use DaydreamLab\User\Requests\Password\Front\PasswordResetFrontStatePost;
use DaydreamLab\User\Requests\Password\Front\PasswordResetFrontSearchPost;


class PasswordResetFrontController extends BaseController
{
    public function __construct(PasswordResetFrontService $service)
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


    public function remove(PasswordResetFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(PasswordResetFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(PasswordResetFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(PasswordResetFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
