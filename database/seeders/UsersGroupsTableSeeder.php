<?php

namespace DaydreamLab\User\Database\Seeders;

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
        $data = json_decode(file_get_contents(__DIR__ . '/jsons/usergroup.json'), true);
        $this->migrate($data, null);
    }


    public function migrate($data, $parent)
    {
        foreach ($data as $item)
        {
            $assets     = $item['assets'];
            $apis       = $item['apis'];
            $children   = $item['children'];
            unset($item['children']);
            unset($item['apis']);
            unset($item['assets']);

            $group = UserGroup::create($item);
            if ($parent)
            {
                $parent->appendNode($group);
            }

            $group->apis()->attach($apis);
            $group->assets()->attach($assets);
            if (count($children))
            {
                self::migrate($children, $group);
            }
        }
    }
}
