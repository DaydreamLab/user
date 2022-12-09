<?php

namespace DaydreamLab\User\Controllers\NotificationTemplate;

use DaydreamLab\User\Controllers\UserController;
use DaydreamLab\User\Requests\NotificationTemplate\NotificationTemplateGetItemRequest;
use DaydreamLab\User\Requests\NotificationTemplate\NotificationTemplateRemoveRequest;
use DaydreamLab\User\Requests\NotificationTemplate\NotificationTemplateSearchRequest;
use DaydreamLab\User\Requests\NotificationTemplate\NotificationTemplateStoreRequest;
use DaydreamLab\User\Services\NotificationTemplate\NotificationTemplateService;
use Throwable;

class NotificationTemplateController extends UserController
{
    protected $modelName = 'NotificationTemplate';

    public function __construct(NotificationTemplateService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function getItem(NotificationTemplateGetItemRequest $request)
    {
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(NotificationTemplateRemoveRequest $request)
    {
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(NotificationTemplateSearchRequest $request)
    {
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(NotificationTemplateStoreRequest $request)
    {
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }
}
