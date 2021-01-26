<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Repositories\User\Admin\UserGroupAdminRepository;
use DaydreamLab\User\Services\User\UserGroupService;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserGroupAdminService extends UserGroupService
{
    use LoggedIn;

    protected $viewlevelAdminService;

    protected $modelType = 'Admin';

    protected $modelName = 'UserGroup';

    protected $search_keys = ['title'];

    public function __construct(UserGroupAdminRepository $repo,
                                ViewlevelAdminService $viewlevelAdminService)
    {
        parent::__construct($repo);
        $this->viewlevelAdminService = $viewlevelAdminService;
        $this->repo = $repo;
    }


    public function addNested(Collection $input)
    {
        $item = parent::addNested($input);

        $super_user = $this->viewlevelAdminService->findBy('title', '=', 'Super User')->first();
        if ($super_user) {
            $rules = $super_user->rules;
            $rules[] = $item->id;
            $super_user->rules = $rules;
            $super_user->save();
        }

        return $item;
    }


    public function addMapping($item, $input)
    {
        $item->assets()->attach($input->get('asset_ids'));
        $item->apis()->attach($input->get('api_ids'));
    }


    public function getItem($input)
    {
        $group = parent::getItem(collect(['id' => $input->get('id')]));
Helper::show($group);
        $group->assets = $group->assets()->get()->map(function ($item){
            return $item->id;
        });

        $group->apis = $group->apis()->get()->map(function ($item){
            return $item->id;
        });

        return $group;
    }


    public function getPage($group_id)
    {
        $group          = $this->find($group_id);

        $group_apis     = $group->apis()->get();
        $assets         = $group->assets()->get()->toTree();

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

        $this->status = 'GetActionSuccess';
        $this->response = $response;

        return $response;
    }


    public function modifyMapping($item, $input)
    {
        $item->assets()->sync($input->get('asset_ids'), true);
        $item->apis()->sync($input->get('api_ids'), true);
    }


    public function removeMapping($item)
    {
        $item->assets()->detach();
        $item->apis()->detach();

        foreach ($item->viewlevels as $viewlevel) {
            $groupIds = [];
            foreach ($viewlevel->rules as $groupId) {
                if ($groupId != $item->id) {
                    $groupIds[] = $groupId;
                }
            }
            $viewlevel->rules = $groupIds;
            $viewlevel->save();
        }
    }
}
