<?php

namespace DaydreamLab\User\Database\Seeds;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Role\RoleApiMap;
use DaydreamLab\User\Models\Role\RoleAssetMap;
use Illuminate\Database\Seeder;
use DaydreamLab\User\Models\Role\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__.'/jsons/role.json'), true);

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

            $role = Role::create($item);
            if ($parent)
            {
                $parent->appendNode($role);
            }

            foreach ($assets as $asset)
            {
                $temp_asset['role_id']  = $role->id;
                $temp_asset['asset_id'] = $asset;
                $temp_asset['created_by'] = 1;
                RoleAssetMap::create($temp_asset);
            }


            foreach ($apis as $api)
            {
                $temp_api['role_id']    = $role->id;
                $temp_api['api_id']     = $api;
                $temp_api['created_by'] = 1;
                RoleApiMap::create($temp_api);
            }


            if (count($children))
            {
                self::migrate($children, $role);
            }
        }

    }
}
