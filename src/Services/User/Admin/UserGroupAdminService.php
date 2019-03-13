<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\NestedServiceTrait;
use DaydreamLab\User\Repositories\User\Admin\UserGroupAdminRepository;
use DaydreamLab\User\Services\User\UserGroupApiMapService;
use DaydreamLab\User\Services\User\UserGroupAssetMapService;
use DaydreamLab\User\Services\User\UserGroupService;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserGroupAdminService extends UserGroupService
{
    use NestedServiceTrait {
        addNested as traitAddNested;
    }

    protected $userGroupApiMapService;

    protected $userGroupAssetMapService;

    protected $viewlevelAdminService;

    protected $type = 'UserGroupAdmin';

    protected $search_keys = ['title'];

    public function __construct(UserGroupAdminRepository $repo,
                                UserGroupApiMapService $userGroupApiMapService,
                                UserGroupAssetMapService $userGroupAssetMapService,
                                ViewlevelAdminService $viewlevelAdminService)
    {
        parent::__construct($repo);
        $this->viewlevelAdminService = $viewlevelAdminService;
        $this->userGroupApiMapService = $userGroupApiMapService;
        $this->userGroupAssetMapService = $userGroupAssetMapService;
        $this->repo = $repo;
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


    public function getAction($group_id)
    {
        $group           = $this->find($group_id);
        $group_apis      = $group->apis;

        $response = $assets = [];
        foreach ($group_apis as $group_api) {
            $temp_api           = $group_api->only('id', 'method');
            $temp_api['name']   = $temp_api['method'];

            $temp_asset             = $group_api->asset->only('id', 'title');
            $temp_asset['disabled'] = true;
            $temp_asset['child']    = [];
            if(!in_array($temp_asset, $assets)) {
                $assets[]   = $temp_asset;
                $response[] = $temp_asset;
            }

            foreach ($response as $key => $item) {
                if ($item['id'] == $temp_asset['id'] && !in_array($temp_api, $item['child'])) {
                    $response[$key]['child'][] = $temp_api;
                }
            }
        }

        $this->status = Str::upper(Str::snake($this->type.'GetActionSuccess'));;
        $this->response = $response;

        return $response;
    }


    public function getApis($group_id)
    {
        $apis = $this->find($group_id)->apis;

        $response = [];
        foreach ($apis as $api) {
            if (!array_key_exists($api->asset_id, $response)) {
                $response[$api->asset_id] = [];
            }
            $response[$api->asset_id][] = $api->method;
        }

        $this->status = Str::upper(Str::snake($this->type.'GetApisSuccess'));;
        $this->response = $response;

        return $apis;
    }


    public function getApiIds($group_id)
    {
        $apis = $this->find($group_id)->apis;
        $ids = [];
        foreach ($apis as $api) {
            $ids[] = $api->id;
        }
        $this->status = Str::upper(Str::snake($this->type.'GetApiIdsSuccess'));;
        $this->response = $ids;

        return $apis;
    }


    public function getPage($group_id)
    {
        $pages = $this->repo->getPage($group_id);
        $this->status = Str::upper(Str::snake($this->type.'GetPageSuccess'));;
        $this->response = $pages;

        return $pages;
    }


    public function search(Collection $input)
    {
        if (!$this->user->isSuperUser())
        {
            $input->put('where', [
                [
                    'key'       => 'title',
                    'operator'  => '!=',
                    'value'     => 'Super User'
                ]
            ]);
        }

        return parent::search($input);
    }


    public function treeList()
    {
        $result =  parent::treeList();

        $data = [];
        foreach ($result as $item)
        {
            if ($item['tree_list_title'] == 'Super User')
            {
                if ($this->user->isSuperUser())
                {
                    $data[] = $item;
                }
            }
            else
            {
                $data[] = $item;
            }
        }

        $this->response = $data;

        return $data;
    }

}
