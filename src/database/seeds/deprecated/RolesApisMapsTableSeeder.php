<?php

namespace DaydreamLab\User\Database\Seeds\Deprecated;

use DaydreamLab\User\Models\Asset\AssetApi;
use DaydreamLab\User\Models\Role\RoleApiMap;
use Illuminate\Database\Seeder;

class RolesApisMapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //super user all 權限
        $assets_apis = AssetApi::all();
        foreach ($assets_apis as $item) {
            RoleApiMap::create([
                'role_id' => 2,
                'api_id' => $item->id,
                'created_by'=> 1,
            ]);
        }

        //Admin
        RoleApiMap::create([
            'role_id' => 3,
            'api_id' => 24,
            'created_by'=> 1,
        ]);

        RoleApiMap::create([
            'role_id' => 3,
            'api_id' => 25,
            'created_by'=> 1,
        ]);

        RoleApiMap::create([
            'role_id' => 3,
            'api_id' => 26,
            'created_by'=> 1,
        ]);

        RoleApiMap::create([
            'role_id' => 3,
            'api_id' => 27,
            'created_by'=> 1,
        ]);
        RoleApiMap::create([
            'role_id' => 3,
            'api_id' => 28,
            'created_by'=> 1,
        ]);

    }
}
