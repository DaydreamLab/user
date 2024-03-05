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
        $redirect   = $input->get('redirect') ?? '/';
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

        $this->storeRedirect($group_id, $redirect);

        return $result;
    }

    public function storeRedirect($group_id, $redirect)
    {
        $group = $this->find($group_id);
        $assets = $group->asset()->get();
        if (! $assets->contains('path', $redirect)) {
            $redirect = $assets
                ->where('parent_id', 1)
                ->sortBy('_lft')
                ->first()['path'];
        }

        if ($redirect) {
            $group->redirect = $redirect;
            $group->save();
        }
    }

}
