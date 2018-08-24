<?php

namespace DaydreamLab\User\Database\Seeds;

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
        User::create([
            'email' => 'technique@daydream-lab.com',
            'password' => bcrypt('daydream5182'),
            'first_name' => 'Daydream Lab',
            'last_name' => 'Engineering Department',
            'redirect' => '/',
            'activation' => 1,
            'activate_token' => 'sdfrwtghkklcxafeg45dfvzczxv',
            'created_by' => 1,
        ]);

        User::create([
            'email' => 'jordan@daydream-lab.com',
            'password' => bcrypt('daydream5182'),
            'first_name' => 'Jordan',
            'last_name' => 'Tsai',
            'redirect' => '/',
            'activation' => 1,
            'activate_token' => 'abcdefghijklmnopqrstuvwxyzff',
            'created_by' => 1,
        ]);

    }
}
