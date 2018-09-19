<?php

namespace DaydreamLab\User\Database\Seeds\Deprecated;

use Illuminate\Database\Seeder;
use DaydreamLab\User\Models\Asset\AssetGroupMap;

class AssetsGroupsMapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssetGroupMap::create([
            'asset_id'    => 2,
            'group_id'    => 1,
            'created_by'  => 1
        ]);

        // 測試用
        AssetGroupMap::create([
            'asset_id'    => 2,
            'group_id'    => 2,
            'created_by'  => 1
        ]);

        AssetGroupMap::create([
            'asset_id'    => 3,
            'group_id'    => 1,
            'created_by'  => 1
        ]);

        AssetGroupMap::create([
            'asset_id'    => 4,
            'group_id'    => 1,
            'created_by'  => 1,
        ]);

        AssetGroupMap::create([
            'asset_id'    => 5,
            'group_id'    => 2,
            'created_by'  => 1
        ]);
    }
}
