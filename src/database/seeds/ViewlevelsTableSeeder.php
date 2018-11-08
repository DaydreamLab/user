<?php

namespace DaydreamLab\User\Database\Seeds;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Viewlevel\Admin\ViewlevelAdmin;
use DaydreamLab\User\Repositories\Viewlevel\Admin\ViewlevelAdminRepository;
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
        $service = new ViewlevelAdminService(new ViewlevelAdminRepository(new ViewlevelAdmin()));

        $service->store(Helper::collect([
            'title'     => 'Public',
            'rules'     => [2],
        ]));

        $service->store(Helper::collect([
            'title'     => 'Guest',
            'rules'     => [3],
        ]));

        $service->store(Helper::collect([
            'title'     => 'Registered',
            'ordering'  => 3,
            'rules'     => [2,4],
        ]));


        $service->store(Helper::collect([
            'title'     => 'Administrator',
            'rules'     => [2,3,4,5],
        ]));

        $service->store(Helper::collect([
            'title'     => 'Super User',
            'ordering'  => 4,
            'rules'     => [2,3,4,5,6],
        ]));
    }
}
