<?php

namespace DaydreamLab\User\Database\Seeds\Deprecated;

use DaydreamLab\User\Models\Asset\AssetApi;
use Illuminate\Database\Seeder;

class AssetsApisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Frontdesks
        AssetApi::create([
            'asset_id' => '5',
            'method' => 'add',
            'url' => '/admin/frontdesk',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '5',
            'method' => 'edit',
            'url' => '/admin/frontdesk',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '5',
            'method' => 'delete',
            'url' => '/admin/frontdesks',
            'created_by' => 1,
        ]);

        //Asset
        AssetApi::create([
            'asset_id' => '8',
            'method' => 'add',
            'url' => '/admin/asset',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '8',
            'method' => 'edit',
            'url' => '/admin/asset',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '8',
            'method' => 'delete',
            'url' => '/admin/assets',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '8',
            'method' => 'updateStatus',
            'url' => '/admin/asset/status',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '8',
            'method' => 'grant',
            'url' => '/admin/asset/*/groups',
            'created_by' => 1,
        ]);

        //Asset Group
        AssetApi::create([
            'asset_id' => '10',
            'method' => 'add',
            'url' => '/admin/assets/group',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '10',
            'method' => 'edit',
            'url' => '/admin/assets/group',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '10',
            'method' => 'delete',
            'url' => '/admin/assets/groups',
            'created_by' => 1,
        ]);

        //Member
        AssetApi::create([
            'asset_id' => '13',
            'method' => 'add',
            'url' => '/admin/user',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '13',
            'method' => 'edit',
            'url' => '/admin/user/*',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '13',
            'method' => 'delete',
            'url' => '/admin/users',
            'created_by' => 1,
        ]);

        //Manager
        AssetApi::create([
            'asset_id' => '16',
            'method' => 'add',
            'url' => '/admin/user',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '16',
            'method' => 'edit',
            'url' => '/admin/user/*',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '16',
            'method' => 'delete',
            'url' => '/admin/users',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '16',
            'method' => 'grant',
            'url' => '/admin/user/*/grant',
            'created_by' => 1,
        ]);


        //Role
        AssetApi::create([
            'asset_id' => '26',
            'method' => 'add',
            'url' => '/admin/role',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '26',
            'method' => 'edit',
            'url' => '/admin/role',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '26',
            'method' => 'delete',
            'url' => '/admin/roles',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '26',
            'method' => 'grant',
            'url' => '/admin/role/*/grant',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '26',
            'method' => 'apis',
            'url' => '/admin/role/*/apis',
            'created_by' => 1,
        ]);

        //Reservation
        AssetApi::create([
            'asset_id' => '31',
            'method' => 'edit',
            'url' => '/admin/reservation/*',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '31',
            'method' => 'update',
            'url' => '/admin/reservation/status',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '31',
            'method' => 'updateRequest',
            'url' => '/admin/request/status',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '31',
            'method' => 'search',
            'url' => '/admin/reservations/search',
            'created_by' => 1,
        ]);

        AssetApi::create([
            'asset_id' => '31',
            'method' => 'add',
            'url' => '/admin/reservations/create',
            'created_by' => 1,
        ]);

        //Check-in & Check-out Log
        AssetApi::create([
            'asset_id' => '34',
            'method' => 'search',
            'url' => '/admin/checkinouthistory/search',
            'created_by' => 1,
        ]);

        //Subscription
        AssetApi::create([
            'asset_id' => '38',
            'method' => 'export',
            'url' => '/',
            'created_by' => 1,
        ]);

    }
}
