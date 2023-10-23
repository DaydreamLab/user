<?php

namespace DaydreamLab\User\Services\UserTagCategory;

use DaydreamLab\Cms\Services\Category\Admin\CategoryAdminService;
use DaydreamLab\User\Services\UserTag\Admin\UserTagAdminService;
use Illuminate\Support\Collection;

class UserTagCategoryService
{
    public $status;

    public $response;

    protected $service;

    protected $userTagAdminService;

    public function __construct(CategoryAdminService $service, UserTagAdminService $userTagAdminService)
    {
        $this->service = $service;
        $this->userTagAdminService = $userTagAdminService;
    }


    public function getItem(Collection $input)
    {
        $result = $this->service->getItem($input);

        $this->service->response->userTags->map(function ($tag) {
            $tag->realTimeUsers = $this->userTagAdminService->getCrmUserIds(
                collect(['rules' => $tag->rules]),
                false
            );
            return $tag;
        });

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
        if (!$input->get('parent_id')) {
            $input->put('parent_id', $this->service->getRoot('usertag')->id);
        }
        $result = $this->service->store($input);
        $this->status = $this->service->status;
        $this->response = $this->service->response;

        return $this->response;
    }


    public function state(Collection $input)
    {
        $result = $this->service->state($input);
        $this->status = $this->service->status;
        $this->response = $this->service->response;
        return $this->response;
    }
}
