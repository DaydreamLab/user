<?php

namespace DaydreamLab\User\Database\Seeders;

use DaydreamLab\User\Models\User\User;
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
            unset($item['groups']);
            $item['password'] = bcrypt($item['password']);
            $user = User::create($item);
            $user->groups()->attach($groups);
        }
    }
}
