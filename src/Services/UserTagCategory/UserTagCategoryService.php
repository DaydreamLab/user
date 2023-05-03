<?php

namespace DaydreamLab\User\Services\UserTagCategory;

use DaydreamLab\Cms\Services\Category\Admin\CategoryAdminService;
use Illuminate\Support\Collection;

class UserTagCategoryService
{
    public $status;

    public $response;

    protected $service;

    public function __construct(CategoryAdminService $service)
    {
        $this->service = $service;
    }


    public function getItem(Collection $input)
    {
        $result = $this->service->getItem($input);
        $this->status = $this->service->status;
        $this->response = $this->service->response;

        return $this->response;
    }


    public function search(Collection $input)
    {
        $input->put('extension', 'usertag');
        $result = $this->service->search($input);
        $this->status = $this->service->status;
        $this->response = $this->service->response;

        return $this->response;
    }


    public function store(Collection $input)
    {
        $input->put('extension', 'usertag');
        $result = $this->service->store($input);
        $this->status = $this->service->status;
        $this->response = $this->service->response;

        return $this->response;
    }


    public function state(Collection $input)
    {
        $input->put('state', -2);
        $result = $this->service->state($input);
        $this->status = $this->service->status;
        $this->response = $this->service->response;
        return $this->response;
    }
}
