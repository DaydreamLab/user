<?php

namespace DaydreamLab\User\Controllers\Role\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Role\Admin\RoleAssetMapAdminService;
use DaydreamLab\User\Requests\Role\Admin\RoleAssetMapAdminRemovePost;
use DaydreamLab\User\Requests\Role\Admin\RoleAssetMapAdminStorePost;
use DaydreamLab\User\Requests\Role\Admin\RoleAssetMapAdminStatePost;
use DaydreamLab\User\Requests\Role\Admin\RoleAssetMapAdminSearchPost;

class RoleAssetMapAdminController extends BaseController
{
    public function __construct(RoleAssetMapAdminService $service)
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


    public function remove(RoleAssetMapAdminRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(RoleAssetMapAdminStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(RoleAssetMapAdminStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(RoleAssetMapAdminSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
}
