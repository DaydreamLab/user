<?php

namespace DaydreamLab\User\Controllers\Asset;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use Illuminate\Support\Collection;
use DaydreamLab\User\Services\Asset\AssetApiService;
use DaydreamLab\User\Requests\Asset\AssetApiRemovePost;
use DaydreamLab\User\Requests\Asset\AssetApiStorePost;
use DaydreamLab\User\Requests\Asset\AssetApiStatePost;
use DaydreamLab\User\Requests\Asset\AssetApiSearchPost;

class AssetApiController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'AssetApi';

    protected $modelType = 'Base';

    public function __construct(AssetApiService $service)
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


    public function remove(AssetApiRemovePost $request)
    {
        $this->service->remove($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function state(AssetApiStatePost $request)
    {
        $this->service->state($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(AssetApiStorePost $request)
    {
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function search(AssetApiSearchPost $request)
    {
        $this->service->search($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }
}
