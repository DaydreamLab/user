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
            'nickname' => '白日夢工程部',
            'redirect' => '/',
            'activation' => 1,
            'activate_token' => 'abcdefghijklmnopqrstuvwxyz',
            'created_by' => 1,
        ]);

        User::create([
            'email' => 'admin@edenaresorts.com',
            'password' => bcrypt('edenaresorts@2018'),
            'first_name' => 'Admin',
            'last_name' => 'Edena',
            'nickname' => '訂房部門',
            'redirect' => '/reservation',
            'activation' => 1,
            'activate_token' => 'qqqqqqqqqqqqqqqqqqqqqqqqqqqqqq',
            'created_by' => 1,
        ]);

        User::create([
            'email' => 'reservations@edenaresorts.com',
            'password' => bcrypt('edenaresorts@2018'),
            'first_name' => 'Rep ( frontdesk )',
            'last_name' => 'Edena',
            'nickname' => '櫃檯部門',
            'redirect' => '/checkin',
            'activation' => 1,
            'activate_token' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'created_by' => 1,
        ]);

        User::create([
            'email' => 'jordan@daydream-lab.com',
            'password' => bcrypt('daydream5182'),
            'first_name' => 'Jordan',
            'last_name' => 'Tsai',
            'nickname' => 'Jordan',
            'redirect' => '/',
            'activation' => 1,
            'activate_token' => '556655665566556655665566',
            'created_by' => 1,
        ]);



    }
}
