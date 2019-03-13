<?php

namespace DaydreamLab\User\Database\Seeds;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Asset\AssetApiMap;
use DaydreamLab\User\Models\User\Admin\UserGroupAdmin;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Models\User\UserGroupApiMap;
use DaydreamLab\User\Models\User\UserGroupAssetMap;
use DaydreamLab\User\Repositories\User\Admin\UserGroupAdminRepository;
use DaydreamLab\User\Repositories\User\UserGroupApiMapRepository;
use DaydreamLab\User\Repositories\User\UserGroupAssetMapRepository;
use DaydreamLab\User\Services\User\Admin\UserGroupAdminService;
use DaydreamLab\User\Services\User\UserGroupApiMapService;
use DaydreamLab\User\Services\User\UserGroupAssetMapService;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use DaydreamLab\User\Repositories\Viewlevel\Admin\ViewlevelAdminRepository;
use DaydreamLab\User\Models\Viewlevel\Admin\ViewlevelAdmin;
use Illuminate\Database\Seeder;

class UsersGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $viewlevelAdminService = new ViewlevelAdminService(new ViewlevelAdminRepository(new ViewlevelAdmin()));

        $userGroupAssetMapService = new UserGroupAssetMapService(new UserGroupAssetMapRepository(new UserGroupAssetMap()));

        $userGroupApiMapService = new UserGroupApiMapService(new UserGroupApiMapRepository(new UserGroupApiMap()));

        $service = new UserGroupAdminService(
            new UserGroupAdminRepository(new UserGroupAdmin()),
            $userGroupApiMapService,
            $userGroupAssetMapService,
            $viewlevelAdminService
        );

        $data = json_decode(file_get_contents(__DIR__.'/jsons/usergroup.json'), true);
        $this->migrate($data, null);

    }


    public function migrate($data, $parent)
    {
        foreach ($data as $item)
        {
            $assets     = $item['assets'];
            $apis       = $item['apis'];
            $children   = $item['children'];
            unset($item['children']);
            unset($item['apis']);
            unset($item['assets']);

            $group = UserGroup::create($item);
            if ($parent)
            {
                $parent->appendNode($group);
            }

            foreach ($apis as $api)
            {
                UserGroupApiMap::create([
                    'group_id'  => $group->id,
                    'api_id'    => $api
                ]);
            }

            foreach ($assets as $asset)
            {
                UserGroupAssetMap::create([
                    'group_id'  => $group->id,
                    'asset_id'  => $asset
                ]);
            }

            if (count($children))
            {
                self::migrate($children, $group);
            }
        }

    }
}
