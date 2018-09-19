<?php

namespace DaydreamLab\User\Database\Seeds\Deprecated;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Models\User\UserGroupMap;
use Illuminate\Database\Seeder;

class UsersGroupsMapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserGroupMap::create([
            'user_id'       => 1,
            'group_id'      => 5,
            'created_by'    => 1
        ]);

        UserGroupMap::create([
            'user_id'       => 4,
            'group_id'      => 5,
            'created_by'    => 1
        ]);

    }
}
