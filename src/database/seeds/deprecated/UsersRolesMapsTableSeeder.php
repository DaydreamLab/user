<?php

namespace DaydreamLab\User\Database\Seeds\Deprecated;

use DaydreamLab\User\Models\User\UserRoleMap;
use Illuminate\Database\Seeder;

class UsersRolesMapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRoleMap::create([
            'user_id' => 2,
            'role_id' => 3,
            'created_by'=>1,
        ]); //最外面

        UserRoleMap::create([
            'user_id' => 3,
            'role_id' => 5,
            'created_by'=>1,
        ]); //最外面

        UserRoleMap::create([
            'user_id' => 1,
            'role_id' => 2,
            'created_by'=>1,
        ]); //最外面

        UserRoleMap::create([
            'user_id' => 4,
            'role_id' => 2,
            'created_by'=>1,
        ]); //最外面


    }
}
