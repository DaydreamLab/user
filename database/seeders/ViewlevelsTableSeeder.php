<?php

namespace DaydreamLab\User\Database\Seeders;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\User\UserGroup;
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

        $jsons = Helper::getJson(__DIR__.'/jsons/viewlevel.json');

        $groups = UserGroup::all();
        foreach ($jsons as $json) {
            $json['groupIds'] = $groups->whereIn('title', $json['groups'])->pluck('id')->all();
            $service->store(collect($json));
        }
    }
}
