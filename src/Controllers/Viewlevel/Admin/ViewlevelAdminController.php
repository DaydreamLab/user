<?php

namespace DaydreamLab\User\Controllers\Viewlevel\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminOrderingPost;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminRemovePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminStorePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminStatePost;
use DaydreamLab\User\Requests\Viewlevel\Admin\ViewlevelAdminSearchPost;

class ViewlevelAdminController extends BaseController
{
    public function __construct(ViewlevelAdminService $service)
    {
        parent::__construct($service);
    }

    public function getItem($id)
    {
        $this->service->canAction('getViewlevel');
        $this->service->getItem($id);

        return $this->response($this->service->status, $this->service->response);
    }


    public function ordering(ViewlevelAdminOrderingPost $request)
    {
        $this->service->canAction('editViewlevel');
        $this->service->ordering($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }



    public function remove(ViewlevelAdminRemovePost $request)
    {
        $this->service->canAction('deleteViewlevel');
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(ViewlevelAdminStatePost $request)
    {
        $this->service->canAction('updateViewlevelState');
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(ViewlevelAdminStorePost $request)
    {
        InputHelper::null($request->validated(), 'id') ? $this->service->canAction('addViewlevel')
            : $this->service->canAction('editViewlevel');
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(ViewlevelAdminSearchPost $request)
    {
        $this->service->canAction('searchViewlevel');
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }
}
