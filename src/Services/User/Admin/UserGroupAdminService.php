<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\NestedServiceTrait;
use DaydreamLab\User\Repositories\User\Admin\UserGroupAdminRepository;
use DaydreamLab\User\Services\User\UserGroupService;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserGroupAdminService extends UserGroupService
{
    use NestedServiceTrait {
        addNested as traitAddNested;
    }

    protected $viewlevelAdminService;

    protected $type = 'UserGroupAdmin';

    protected $search_keys = ['title'];

    public function __construct(UserGroupAdminRepository $repo,
                                ViewlevelAdminService $viewlevelAdminService)
    {
        parent::__construct($repo);
        $this->viewlevelAdminService = $viewlevelAdminService;
        $this->repo = $repo;
    }

    public function getItem($id, $diff = false)
    {
        $group = parent::getItem($id, $diff);

        $group->assets = $group->asset()->get()->map(function ($item){
            return $item->id;
        });

        $group->apis = $group->api()->get()->map(function ($item){
            return $item->id;
        });


        return $group;
    }


    public function addNested(Collection $input)
    {
        $item = $this->traitAddNested($input);
        $super_user = $this->viewlevelAdminService->findBy('title', '=', 'Super User')->first();
        if ($super_user)
        {
            $rules = $super_user->rules;
            $rules[] = $item->id;
            $super_user->rules = $rules;
            $super_user->save();
        }
        return $item;
    }


    public function getPage($group_id)
    {
        $group          = $this->find($group_id);

        $group_apis     = $group->api()->get();
        $assets         = $group->asset()->get()->toTree();

        $apis = [];
        foreach ($group_apis as $group_api)
        {
            $temp_api           = $group_api->only('id', 'method');
            $temp_api['name']   = $temp_api['method'];

            $temp_api_asset_id  = $group_api->asset->id;
            if (!array_key_exists($temp_api_asset_id, $apis))
            {
                $apis[$temp_api_asset_id] = [];
            }
            $apis[$temp_api_asset_id][] = $temp_api;
        }

        $response['apis']       = $apis;
        $response['assets']    = $assets;

        $this->status = Str::upper(Str::snake($this->type.'GetActionSuccess'));;
        $this->response = $response;

        return $response;
    }


    public function store(Collection $input, $diff = false)
    {
        $api_ids    = $input->api_ids;
        $asset_ids  = $input->asset_ids;
        $input->forget('api_ids');
        $input->forget('asset_ids');

        $result =  parent::store($input);
        $group_id = gettype($result) == 'boolean' ? $input->id : $result->id;

        // todo: 需要塞入access

        return $result;
    }

}
