<?php

namespace DaydreamLab\User\Database\Seeds;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Database\Seeder;

class UsersGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $root =  UserGroup::create([
            'title'         => 'ROOT',
            'description'   => 'ROOT',
            'ordering'      => 1,
            'created_by'    => 1,
        ]);


        $public = UserGroup::create([
            'title'         => 'Public',
            'description'   => 'Public',
            'ordering'      => 1,
            'created_by'    => 1,
        ]);

        $guest = UserGroup::create([
            'title'         => 'Guest',
            'description'   => 'Guest',
            'ordering'      => 1,
            'created_by'    => 1,
        ]);

        $registered = UserGroup::create([
            'title'         => 'Registered',
            'description'   => 'Registered',
            'ordering'      => 2,
            'created_by'    => 2,
        ]);


        $superuser = UserGroup::create([
            'title'         => 'Super User',
            'description'   => 'Super User',
            'ordering'      => 3,
            'created_by'    => 1,
        ]);


        $root->appendNode($public);
        $public->appendNode($guest);
        $public->appendNode($registered);
        $public->appendNode($superuser);

    }
}
