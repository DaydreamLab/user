<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Repositories\Asset\Admin\AssetApiAdminRepository;
use DaydreamLab\User\Services\Asset\AssetApiService;
use Illuminate\Support\Collection;

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
        $super = UserGroup::where('title', 'Super User')->first();
        if ($super) {
            $item->userGroups()->attach($super->id);
        }
        $admin = UserGroup::where('title', 'Administrator')->first();
        if ($admin) {
            $item->userGroups()->attach($admin->id);
        }
        $item->assets()->attach($input->get('asset_id'));
    }


    public function modifyMapping($item, $input)
    {
        return $item->assets()->sync($input->get('asset_id'));
    }


    public function removeMapping($item)
    {
        return $item->assets()->detach() && $item->userGroups()->detach();
    }
}
