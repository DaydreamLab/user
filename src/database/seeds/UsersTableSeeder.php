<?php

namespace DaydreamLab\User\Database\Seeds;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserGroupMap;
use DaydreamLab\User\Models\User\UserRoleMap;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__.'/jsons/user.json'), true);

        $this->migrate($data, null);
    }

    public function migrate($data, $parent)
    {
        foreach ($data as $item)
        {
            $groups     = $item['groups'];
            $roles      = $item['roles'];
            unset($item['groups']);
            unset($item['roles']);

            $item['password'] = bcrypt($item['password']);
            $user = User::create($item);
            foreach ($groups as $group)
            {
                $temp_group['user_id']  = $user->id;
                $temp_group['group_id'] = $group;
                $temp_group['created_by'] = 1;
                UserGroupMap::create($temp_group);
            }

            foreach ($roles as $role)
            {
                $temp_role['user_id']  = $user->id;
                $temp_role['role_id'] = $role;
                $temp_role['created_by'] = 1;
                UserRoleMap::create($temp_role);
            }
        }

    }
}
