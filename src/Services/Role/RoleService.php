<?php

namespace DaydreamLab\User\Services\Role;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Role\RoleRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RoleService extends BaseService
{
    protected $type = 'Role';

    public function __construct(RoleRepository $repo)
    {
        parent::__construct($repo);
    }


    public function getAction($id)
    {
        $role   = $this->find($id);
        $assets = $role->assets;
        $apis   = $role->apis;

        Helper::show($apis->toArray());
        exit();

        $data = [];
        foreach ($assets as $asset) {
            $temp_asset = $asset->only('id', 'title');
            $temp_asset['name']     = $temp_asset['title'];
            $temp_asset['asset_id'] = $temp_asset['id'];
            $temp_asset['disabled'] = true;

            foreach ($apis as $api) {

                $temp_api = $api->only('id', 'method');
                $temp_api['name'] = $temp_api['method'];
                $temp_asset['child'][] = (object)$temp_api;
            }
            $data[] = $temp_asset;
        }

        Helper::show($data);
        exit();
    }


    public function getApis($role_id)
    {
        $apis = $this->find($role_id)->apis;
        $ids = [];
        foreach ($apis as $api) {
            $ids[] = $api->id;
        }
        $this->status = Str::upper(Str::snake($this->type.'GetApisSuccess'));;
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
        return parent::storeNested($input);
    }
}
