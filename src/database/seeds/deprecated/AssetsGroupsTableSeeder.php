<?php

namespace DaydreamLab\User\Database\Seeds\Deprecated;

use Illuminate\Database\Seeder;
use DaydreamLab\User\Models\Asset\AssetGroup;

class AssetsGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssetGroup::create([
            'title'       => 'Group A',
            'state'    => 1,
            'created_by' => 1
        ]);
        AssetGroup::create([
            'title'       => 'Group B',
            'state'    => 1,
            'created_by' => 1
        ]);
        AssetGroup::create([
            'title'       => 'Group C',
            'state'   => 1,
            'created_by' => 1
        ]);
    }
}
