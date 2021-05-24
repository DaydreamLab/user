<?php

namespace DaydreamLab\User\Database\Seeders;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserGroup;
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
        $data = Helper::getJson(__DIR__ . '/jsons/user.json', true);

        $this->migrate($data, null);
    }

    public function migrate($data, $parent)
    {
        foreach ($data as $item)
        {
            $groups     = $item['groups'];
            unset($item['groups']);

            $groups = UserGroup::whereIn('title', $groups)->pluck('id');

            $item['password'] = bcrypt($item['password']);
            $user = User::create($item);
            $user->groups()->attach($groups);
        }
    }
}
