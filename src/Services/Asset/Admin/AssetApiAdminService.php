<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Repositories\Asset\Admin\AssetApiAdminRepository;
use DaydreamLab\User\Services\Asset\AssetApiService;

class AssetApiAdminService extends AssetApiService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    protected $search_keys = ['method'];

    public function __construct(AssetApiAdminRepository $repo)
    {
        parent::__construct($repo);
    }


    public function addMapping($item, $input)
    {
        $item->assets()->attach($input->get('asset_id'));
    }


    public function modifyMapping($item, $input)
    {
        return $item->assets()->sync($input->get('asset_id'));
    }


    public function removeMapping($item)
    {
        return $item->assets()->detach();
    }
}
