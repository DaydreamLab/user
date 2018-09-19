<?php

namespace DaydreamLab\User\Database\Seeds\Deprecated;

use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Role\RoleAssetMap;
use Illuminate\Database\Seeder;

class RolesAssetsMapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $assets = Asset::where('id', '>', 1)->get();

        foreach ($assets as $item) {
            RoleAssetMap::create([
                'role_id'       => 2,
                'asset_id'      => $item->id,
                'created_by'    => 1,
            ]);
        }

        //Admin
        //Check-in
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 19,
            'created_by'    => 1,
        ]);

        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 20,
            'created_by'    => 1,
        ]);

        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 21,
            'created_by'    => 1,
        ]);
        //Check-out
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 22,
            'created_by'    => 1,
        ]);
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 23,
            'created_by'    => 1,
        ]);
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 24,
            'created_by'    => 1,
        ]);

        //Reservation
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 27,
            'created_by'    => 1,
        ]);

        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 28,
            'created_by'    => 1,
        ]);

        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 29,
            'created_by'    => 1,
        ]);
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 30,
            'created_by'    => 1,
        ]);
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 31,
            'created_by'    => 1,
        ]);
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 32,
            'created_by'    => 1,
        ]);
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 33,
            'created_by'    => 1,
        ]);
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 34,
            'created_by'    => 1,
        ]);

        //Subscription
        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 37,
            'created_by'    => 1,
        ]);

        RoleAssetMap::create([
            'role_id'       => 3,
            'asset_id'      => 38,
            'created_by'    => 1,
        ]);

        //Frontdesk
        //Check-in
        RoleAssetMap::create([
            'role_id'       => 5,
            'asset_id'      => 19,
            'created_by'    => 1,
        ]);

        RoleAssetMap::create([
            'role_id'       => 5,
            'asset_id'      => 20,
            'created_by'    => 1,
        ]);

        RoleAssetMap::create([
            'role_id'       => 5,
            'asset_id'      => 21,
            'created_by'    => 1,
        ]);
        //Check-out
        RoleAssetMap::create([
            'role_id'       => 5,
            'asset_id'      => 22,
            'created_by'    => 1,
        ]);
        RoleAssetMap::create([
            'role_id'       => 5,
            'asset_id'      => 23,
            'created_by'    => 1,
        ]);
        RoleAssetMap::create([
            'role_id'       => 5,
            'asset_id'      => 24,
            'created_by'    => 1,
        ]);

    }
}
