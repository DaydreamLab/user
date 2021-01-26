<?php

namespace DaydreamLab\User\Controllers\Asset;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\AssetGroupService;
use DaydreamLab\User\Requests\Asset\AssetGroupRemovePost;
use DaydreamLab\User\Requests\Asset\AssetGroupStorePost;
use DaydreamLab\User\Requests\Asset\AssetGroupStatePost;
use DaydreamLab\User\Requests\Asset\AssetGroupSearchPost;

class AssetGroupController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'AssetGroup';

    protected $modelType = 'Base';

    public function __construct(AssetGroupService $service)
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


    public function remove(AssetGroupRemovePost $request)
    {
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetGroupStatePost $request)
    {
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetGroupStorePost $request)
    {
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(AssetGroupSearchPost $request)
    {
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }
}
