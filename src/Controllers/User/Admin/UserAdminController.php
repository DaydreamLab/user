<?php

namespace DaydreamLab\User\Controllers\User\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\User\Requests\User\Admin\UserAdminBlockPost;
use DaydreamLab\User\Requests\User\Admin\UserAdminGetItem;
use DaydreamLab\User\Resources\User\Admin\Collections\UserAdminListResourceCollection;
use DaydreamLab\User\Resources\User\Admin\Models\UserAdminResource;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use DaydreamLab\User\Requests\User\Admin\UserAdminRemovePost;
use DaydreamLab\User\Requests\User\Admin\UserAdminStorePost;
use DaydreamLab\User\Requests\User\Admin\UserAdminSearchPost;
use Illuminate\Http\Request;

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


    public function block(UserAdminBlockPost $request)
    {
        $this->service->setUser($request->user());
        $this->service->block($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function getItem(UserAdminGetItem $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->getItem(collect(['id' => $request->route('id')]));

        return $this->response($this->service->status, new UserAdminResource($this->service->response));
    }


    public function getSelfPage(Request $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->getSelfPage();

        return $this->response($this->service->status, $this->service->response);
    }


    public function remove(UserAdminRemovePost $request)
    {
        $this->service->setUser($request->user());
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserAdminStorePost $request)
    {
        $input = $request->validated();

        if (!InputHelper::null($input, 'activation')) {
            $activation = $input->get('activation');
            $input->forget('activation');
            if($activation == 'false') {
                $input->put('activation', 0);
            } else {
                $input->put('activation', 1);
            }
        }

        $this->service->setUser($request->user());
        $this->service->store($input);

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(UserAdminSearchPost $request)
    {
        $this->service->setUser($request->user());
        $this->service->search($request->validated());

        return $this->response($this->service->status, new UserAdminListResourceCollection($this->service->response));
    }
}
