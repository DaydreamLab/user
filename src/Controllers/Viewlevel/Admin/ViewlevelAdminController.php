<?php

namespace DaydreamLab\User\Controllers\Viewlevel\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminGetItem;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminOrderingPost;
use DaydreamLab\User\Resources\Viewlevel\Admin\Collections\ViewlevelAdminListResourceCollection;
use DaydreamLab\User\Resources\Viewlevel\Admin\Models\ViewlevelAdminResource;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminRemovePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminStorePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminStatePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminSearchPost;
use Illuminate\Http\Request;

class ViewlevelAdminController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'Viewlevel';

    protected $modelType = 'Admin';

    public function __construct(ViewlevelAdminService $service)
    {
        parent::__construct($service);
    }

    public function getItem(ViewlevelAdminGetItem $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->getItem(collect(['id' => $request->route('id')]));

        return $this->response($this->service->status, new ViewlevelAdminResource($this->service->response));
    }


    public function ordering(ViewlevelAdminOrderingPost $request)
    {
        $this->service->setUser($request->user());
        $this->service->ordering($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(ViewlevelAdminRemovePost $request)
    {
        $this->service->setUser($request->user());
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(ViewlevelAdminStatePost $request)
    {
        $this->service->setUser($request->user());
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(ViewlevelAdminStorePost $request)
    {
        $this->service->setUser($request->user());
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(ViewlevelAdminSearchPost $request)
    {
        $this->service->setUser($request->user());
        $this->service->search($request->validated());

        return $this->response($this->service->status, new ViewlevelAdminListResourceCollection($this->service->response));
    }
}
