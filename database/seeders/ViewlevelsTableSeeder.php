<?php

namespace DaydreamLab\User\Database\Seeders;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
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
        $service = app(ViewlevelAdminService::class);

        $service->store(collect([
            'title'     => 'Public',
            'rules'     => [2],
        ]));

        $service->store(collect([
            'title'     => 'Guest',
            'rules'     => [3],
        ]));

        $service->store(collect([
            'title'     => 'Registered',
            'ordering'  => 3,
            'rules'     => [2,4],
        ]));


        $service->store(collect([
            'title'     => 'Administrator',
            'rules'     => [2,3,4,5],
        ]));

        $service->store(collect([
            'title'     => 'Super User',
            'ordering'  => 4,
            'rules'     => [2,3,4,5,6],
        ]));
    }
}
