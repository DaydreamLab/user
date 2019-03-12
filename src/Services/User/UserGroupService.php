<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\NestedServiceTrait;
use DaydreamLab\User\Repositories\User\UserGroupRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserGroupService extends BaseService
{
    use NestedServiceTrait ;

    protected $type = 'UserGroup';

    public function __construct(UserGroupRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }

    public function getAction($id)
    {
        $role           = $this->find($id);
        $role_apis      = $role->apis;

        $response = $assets = [];
        foreach ($role_apis as $role_api) {
            $temp_api           = $role_api->only('id', 'method');
            $temp_api['name']   = $temp_api['method'];

            $temp_asset             = $role_api->asset->only('id', 'title');
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


    public function getApis($role_id)
    {
        $apis = $this->find($role_id)->apis;

        $this->status = Str::upper(Str::snake($this->type.'GetApisSuccess'));;
        $this->response = $apis;

        return $apis;
    }


    public function getApiIds($role_id)
    {
        $apis = $this->find($role_id)->apis;
        $ids = [];
        foreach ($apis as $api) {
            $ids[] = $api->id;
        }
        $this->status = Str::upper(Str::snake($this->type.'GetApisIdsSuccess'));;
        $this->response = $ids;

        return $apis;
    }


    public function getPage($role_id)
    {
        $pages = $this->repo->getPage($role_id);
        $this->status = Str::upper(Str::snake($this->type.'GetPageSuccess'));;
        $this->response = $pages;

        return $pages;
    }


    public function getTree()
    {
        $items = $this->repo->getTree();
        $this->status = Str::upper(Str::snake($this->type.'GetTreeSuccess'));;
        $this->response = $items;

        return $items;
    }


    public function store(Collection $input)
    {
        return $this->storeNested($input);
    }


    public function tree()
    {
        $tree = $this->findBy('id', '!=', 1)->toTree();

        $this->status =  Str::upper(Str::snake($this->type . 'GetTreeSuccess'));
        $this->response = $tree;

        return $tree;
    }


//    public function treeList()
//    {
//        $tree = $this->findBy('id', '!=', 1)->toFlatTree();
//
//        $tree = $tree->map(function ($item, $key) {
//            return $item->only(['id', 'tree_list_title']);
//        });
//
//        $this->status =  Str::upper(Str::snake($this->type . 'GetTreeListSuccess'));
//        $this->response = $tree;
//
//        return $tree;
//    }

    public function treeList()
    {
        $with_ancestor_viewlevels = [];
        foreach ($this->user->viewlevels as $viewlevel)
        {
            $with_ancestor_viewlevels[] = $viewlevel;
            $group = $this->find($viewlevel);
            foreach ($group->ancestors as $ancestor)
            {
                if ($ancestor->id != 1 && !in_array($ancestor->id, $with_ancestor_viewlevels))
                {
                    $with_ancestor_viewlevels[] = $ancestor->id;
                }
            }

        }

        $tree = $this->findBySpecial('whereIn', 'id', $with_ancestor_viewlevels)->toFlatTree();

        $viewlevels = $this->user->viewlevels;
        $tree = $tree->map(function ($item, $key) use ($viewlevels) {
            if (in_array($item->id, $viewlevels))
            {
                $item->disabled = 0;
            }
            else
            {
                $item->disabled = 1;
            }
            return $item->only(['id', 'tree_list_title', 'disabled']);
        });

        $this->status =  Str::upper(Str::snake($this->type . 'GetTreeListSuccess'));
        $this->response = $tree;

        return $tree;
    }
}
