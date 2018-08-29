<?php

namespace DaydreamLab\User\Database\Seeds;

use Illuminate\Database\Seeder;
use DaydreamLab\User\Models\Role\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'title'      => 'ROOT',
            'state'   => 1,
            'redirect'  => '/',
            'canDelete' => 0,
            'created_by'=> 1,
            'children'  => [
                [
                    'title' => 'Super User',
                    'state' => 1,
                    'redirect' => '/',
                    'canDelete' =>0,
                    'created_by'=> 1,
                ],
                [
                    'title' => 'Admin',
                    'state' => 1,
                    'redirect' => '/reservation',
                    'canDelete' => 0,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Account Manager',
                            'state' => 1,
                            'redirect' => '/users',
                            'canDelete' =>0,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Rep(frontdesk)',
                            'state' => 1,
                            'redirect' => '/checkin',
                            'canDelete' =>0,
                            'created_by'=> 1,
                        ],
                    ],
                ],
                [
                    'title' => 'Guest',
                    'state' => 1,
                    'redirect' => '/',
                    'canDelete' =>0,
                    'created_by'=> 1,
                ],
            ],
        ]); //最外面
    }
}
