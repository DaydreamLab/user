<?php

namespace DaydreamLab\User\Services\Role;

use DaydreamLab\JJAJ\Traits\NestedServiceTrait;
use DaydreamLab\User\Repositories\Role\RoleRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RoleService extends BaseService
{
    use NestedServiceTrait;

    protected $type = 'Role';

    public function __construct(RoleRepository $repo)
    {
        parent::__construct($repo);
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
        $this->status = Str::upper(Str::snake($this->type.'GetTreeSuccess'));;
        $this->response = $this->repo->getTree();

        return true;
    }


    public function store(Collection $input)
    {
        return $this->storeNested($input);
    }


    public function remove(Collection $input)
    {
        return $this->removeNested($input);
    }
}
