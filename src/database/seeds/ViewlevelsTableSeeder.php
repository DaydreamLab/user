<?php

namespace DaydreamLab\User\Database\Seeds;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Models\Viewlevel\Viewlevel;
use Illuminate\Database\Seeder;

class ViewlevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Viewlevel::create([
            'title'     => 'Public',
            'ordering'  => 1,
            'rules'     => [2],
            'created_by'=> 1
        ]);

        Viewlevel::create([
            'title'     => 'Guest',
            'ordering'  => 2,
            'rules'     => [3],
            'created_by'=> 1
        ]);

        Viewlevel::create([
            'title'     => 'Registered',
            'ordering'  => 3,
            'rules'     => [4],
            'created_by'=> 1
        ]);

        Viewlevel::create([
            'title'     => 'Super User',
            'ordering'  => 4,
            'rules'     => [5],
            'created_by'=> 1
        ]);


    }


}
