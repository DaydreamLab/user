<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Requests\User\Admin\UserAdminExportPost;
use DaydreamLab\User\Requests\User\Admin\UserAdminBlockPost;
use DaydreamLab\User\Requests\User\Admin\UserAdminGetItem;
use DaydreamLab\User\Requests\User\Admin\UserAdminSendTotpPost;
use DaydreamLab\User\Resources\User\Admin\Collections\UserAdminExportResourceCollection;
use DaydreamLab\User\Resources\User\Admin\Collections\UserAdminListResourceCollection;
use DaydreamLab\User\Resources\User\Admin\Models\UserAdminResource;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use DaydreamLab\User\Requests\User\Admin\UserAdminRemovePost;
use DaydreamLab\User\Requests\User\Admin\UserAdminStorePost;
use DaydreamLab\User\Requests\User\Admin\UserAdminSearchPost;
use Illuminate\Http\Request;
use Throwable;

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


    public function export(UserAdminExportPost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->export($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], UserAdminExportResourceCollection::class);
    }


    public function block(UserAdminBlockPost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->block($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function getItem(UserAdminGetItem $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getItem(collect(['id' => $request->route('id')]));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], UserAdminResource::class);
    }


    public function getSelfPage(Request $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->getSelfPage($request->get('site_id') ? :1);
        } catch (Throwable $t) {
           $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(UserAdminRemovePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->remove($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(UserAdminSearchPost $request)
    {
        $this->service->setUser($request->user());
        try {
            $this->service->search($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], UserAdminListResourceCollection::class);
    }


    public function store(UserAdminStorePost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->store($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, [], UserAdminResource::class);
    }


    public function sendTotp(UserAdminSendTotpPost $request)
    {
        $this->service->setUser($request->user('api'));
        try {
            $this->service->sendTotp($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response, []);
    }
}
