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

            $temp_asset['id']       = $group_api->asset->id;
            $temp_asset['name']     = $group_api->asset->title;
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


    public function getPage($group_id)
    {
        $pages = $this->repo->getPage($group_id);
        $this->status = Str::upper(Str::snake($this->type.'GetPageSuccess'));;
        $this->response = $pages;

        return $pages;
    }



    public function store(Collection $input)
    {
        $api_ids    = $input->api_ids;
        $asset_ids  = $input->asset_ids;
        $input->forget('api_ids');
        $input->forget('asset_ids');

        $result =  parent::store($input);
        $group_id = gettype($result) == 'boolean' ? $input->id : $result->id;

        // todo: 需要塞入access

        $this->userGroupApiMapService->storeKeysMap(Helper::collect([
            'group_id' => $group_id,
            'api_ids'   => $api_ids
        ]));


        $this->userGroupAssetMapService->storeKeysMap(Helper::collect([
            'group_id'  => $group_id,
            'asset_ids' => $asset_ids
        ]));

        return $result;
    }

}
