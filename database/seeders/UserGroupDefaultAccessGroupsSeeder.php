<?php

namespace DaydreamLab\User\Database\Seeders;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Database\Seeder;

class UserGroupDefaultAccessGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsons = Helper::getJson(__DIR__.'/jsons/usergroup-default-access-groups.json');

        $groups = UserGroup::all();
        foreach ($jsons as $json) {
            $targetUserGroup = $groups->where('title', $json['title'])->first();
            $targetAccessGroups = $groups->whereIn('title', $json['groups']);
            $targetUserGroup->defaultAccessGroups()->attach($targetAccessGroups->pluck('id')->all());
        }
    }
}
