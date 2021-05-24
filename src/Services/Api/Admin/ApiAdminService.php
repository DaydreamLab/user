<?php

namespace DaydreamLab\User\Services\Api\Admin;

use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Repositories\Api\Admin\ApiAdminRepository;
use DaydreamLab\User\Services\Api\ApiService;

class ApiAdminService extends ApiService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    protected $search_keys = ['method'];

    public function __construct(ApiAdminRepository $repo)
    {
        parent::__construct($repo);
    }


    public function addMapping($item, $input)
    {
        if (count($input->get('assetIds') ?: [])) {
            $item->assets()->attach($input->get('assetIds'));
        }
    }


    public function modifyMapping($item, $input)
    {
        $item->assets()->sync($input->get('assetIds') ?: []);
    }


    public function beforeRemove($item)
    {
        $item->assets()->detach();
        $item->usergroups()->detach();
    }
}
