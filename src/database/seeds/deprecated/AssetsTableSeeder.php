<?php

namespace DaydreamLab\User\Database\Seeds\Deprecated;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Asset\AssetRepository;
use DaydreamLab\User\Services\Asset\AssetService;
use Illuminate\Database\Seeder;
use DaydreamLab\User\Models\Asset\Asset;
use Illuminate\Support\Collection;

class AssetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    /**
     * Anna Waring: 如果要新增 asset 請新增在最後面，以免 asset id 變動影響到 api 的設定
     * 中間要新增 asset 更新 AssetsAPIsTableSeeder.php, RolesAssetsMapTableSeeder.php,  RolesAssetapisMapTableSeeder.php
     */
    public function run()
    {
        $this->migrate1();
        //$this->migrateTest();

        $service    = new AssetService(new AssetRepository(new Asset()));

        $combine_path = function ($parent_id, $full_path) use (&$combine_path, $service) {
            if($parent_id == 1) {
                return $full_path;
            }
            else {
                $parent = $service->find($parent_id);
                $full_path = $parent->path . $full_path;
                return $combine_path($parent->parent_id, $full_path);
            }
        };

        $assets     = $service->search(Helper::collect(['limit' => 10000]));
        $assets->forget('pagination');
        foreach ($assets as $asset) {
            $full_path = $asset->path;
            $asset->full_path = $combine_path($asset->parent_id, $full_path);
            $asset->save();
        }

    }


    public function migrate1()
    {
        Asset::create([

            'title'      => 'ROOT',
            'path'      => '',
            'component' => '',
            'type'      => '',
            'redirect'  => '',
            'icon'      => '',
            'state'     => 1,
            'showNav'   => 1,
            'ordering'  => 1,
            'created_by'=> 1,
            'children'  => [
                [
                    'title' => '',
                    'path' => '/',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => '',
                    'icon' => 'home',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 1,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Dashboard',
                            'path' => '',
                            'component' => 'index',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'home',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                    ],
                ],
                [
                    'title' => 'Frontdesks',
                    'path' => '/frontdesk',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'user',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 5,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Frontdesks List',
                            'path' => '',
                            'component' => 'frontdesk/list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'user',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Edit Frontdesk',
                            'path' => '/edit',
                            'component' => 'frontdesk/edit',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'th-list',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 2,
                            'created_by'=> 1,
                        ],
                    ],
                ],
                [
                    'title' => 'Assets',
                    'path' => '/asset',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'th-list',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 8,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Assets List',
                            'path' => '',
                            'component' => 'asset/list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'th-list',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Edit Asset',
                            'path' => '/edit',
                            'component' => 'asset/edit',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'th-list',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 2,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Asset Group',
                            'path' => '/group',
                            'component' => 'asset/group_list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'th-list',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 3,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Edit Asset Group',
                            'path' => '/group/edit',
                            'component' => 'asset/group_edit',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'th-list',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 4,
                            'created_by'=> 1,
                        ],
                    ],
                ],
                [
                    'title' => 'Members',
                    'path' => '/member',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'users',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 6,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Member List',
                            'path' => '',
                            'component' => 'member/list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'users',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Edit User',
                            'path' => '/edit',
                            'component' => 'member/edit',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'users',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 2,
                            'created_by'=> 1,
                        ],
                    ],
                ],
                [
                    'title' => 'Managers',
                    'path' => '/manager',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'user-cog',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 7,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Manager List',
                            'path' => '',
                            'component' => 'manager/list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'user-cog',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Edit Manager',
                            'path' => '/edit',
                            'component' => 'manager/edit',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'user-cog',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Assign Role for Manager',
                            'path' => '/grant',
                            'component' => 'manager/grant',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'user-cog',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                    ],
                ],
                [
                    'title' => 'Check-in',
                    'path' => '/checkin',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'calendar-check',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 4,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Check-in List',
                            'path' => '',
                            'component' => 'checkin/list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'calendar-check',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Check-in Progress',
                            'path' => '/form',
                            'component' => 'checkin/form',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'calendar-check',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 2,
                            'created_by'=> 1,
                        ],

                    ],
                ],
                [
                    'title' => 'Check-out',
                    'path' => '/checkout',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'calendar-check',
                    'state' => 1,
                    'showNav' => 0,
                    'ordering' => 5,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Check-out List',
                            'path' => '',
                            'component' => 'checkout/list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'calendar-check',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Check-out Progress',
                            'path' => '/form',
                            'component' => 'checkout/form',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'calendar-check',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 2,
                            'created_by'=> 1,
                        ],

                    ],
                ],
                [
                    'title' => 'Roles',
                    'path' => '/role',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'user',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 9,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Roles List',
                            'path' => '',
                            'component' => 'role/list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'user',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Edit Role',
                            'path' => '/edit',
                            'component' => 'role/edit',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'user',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 2,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Grant Role Permission',
                            'path' => '/grant',
                            'component' => 'role/grant',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'user',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 3,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Grant Role Apis',
                            'path' => '/apis',
                            'component' => 'role/apis',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'user',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 4,
                            'created_by'=> 1,
                        ],
                    ],
                ],
                [
                    'title' => 'Reservation',
                    'path' => '/reservation',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'clipboard-list',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 3,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Reservation List',
                            'path' => '',
                            'component' => 'reservation/list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'clipboard-list',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Add Reservation',
                            'path' => '/add',
                            'component' => 'reservation/add',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'clipboard-list',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 2,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Edit Reservation',
                            'path' => '/edit',
                            'component' => 'reservation/edit',
                            'type' => 'function',
                            'redirect' => '',
                            'icon' => 'clipboard-list',
                            'state' => 1,
                            'showNav' => 0,
                            'ordering' => 3,
                            'created_by'=> 1,
                        ],
                        [
                            'title' => 'Check In/Out Log',
                            'path' => '/log',
                            'component' => 'checkin/log',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'clipboard-list',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 4,
                            'created_by'=> 1,
                        ],

                    ],
                ],
                [
                    'title' => 'System',
                    'path' => '/system',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'cogs',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 10,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Vue Error Log',
                            'path' => '/vue-error-log',
                            'component' => 'system/vue-error-log',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'cogs',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                    ],
                ],
                [
                    'title' => 'Subscription',
                    'path' => '/subscription',
                    'component' => '',
                    'type' => 'alias',
                    'redirect' => 'noredirect',
                    'icon' => 'newspaper',
                    'state' => 1,
                    'showNav' => 1,
                    'ordering' => 2,
                    'created_by'=> 1,
                    'children' => [
                        [
                            'title' => 'Subscription List',
                            'path' => '',
                            'component' => 'subscription/list',
                            'type' => 'menu',
                            'redirect' => '',
                            'icon' => 'newspaper',
                            'state' => 1,
                            'showNav' => 1,
                            'ordering' => 1,
                            'created_by'=> 1,
                        ],
                    ],
                ],

            ],
        ]); //最外面
    }

    public function migrateTest()
    {
        Asset::create([
            'title'         => 'ROOT',
            'path'          => '',
            'component'     => '',
            'type'          => '',
            'redirect'      => '',
            'icon'          => '',
            'state'         => 1,
            'showNav'       => 1,
            'ordering'      => 1,
            'created_by'    => 1,
            'children'      => [
                [
                    'title'         => 'A',
                    'path'          => '/A',
                    'component'     => 'a',
                    'type'          => 'a',
                    'redirect'      => 'a',
                    'icon'          => 'a',
                    'state'         => 1,
                    'showNav'       => 1,
                    'ordering'      => 1,
                    'created_by'    => 1,
                    'children'      => [
                        [
                            'title'         => 'A1',
                            'path'          => '/A1',
                            'component'     => 'a1',
                            'type'          => 'a1',
                            'redirect'      => 'a1',
                            'icon'          => 'a1',
                            'state'         => 1,
                            'showNav'       => 1,
                            'ordering'      => 1,
                            'created_by'    => 1,
                        ],
                        [
                            'title'         => 'A2',
                            'path'          => '/A2',
                            'component'     => 'a2',
                            'type'          => 'a2',
                            'redirect'      => 'a2',
                            'icon'          => 'a2',
                            'state'         => 1,
                            'showNav'       => 1,
                            'ordering'      => 2,
                            'created_by'    => 1,
                        ],
                        [
                            'title'         => 'A3',
                            'path'          => '/A3',
                            'component'     => 'a3',
                            'type'          => 'a3',
                            'redirect'      => 'a3',
                            'icon'          => 'a3',
                            'state'         => 1,
                            'showNav'       => 1,
                            'ordering'      => 3,
                            'created_by'    => 1,
                        ],
                    ]
                ],
                [
                    'title'         => 'B',
                    'path'          => '/B',
                    'component'     => 'b',
                    'type'          => 'b',
                    'redirect'      => 'b',
                    'icon'          => 'b',
                    'state'         => 1,
                    'showNav'       => 1,
                    'ordering'      => 2,
                    'created_by'    => 1,
                    'children'      => [
                        [
                            'title'         => 'B1',
                            'path'          => '/B1',
                            'component'     => 'b1',
                            'type'          => 'b1',
                            'redirect'      => 'b1',
                            'icon'          => 'b1',
                            'state'         => 1,
                            'showNav'       => 1,
                            'ordering'      => 1,
                            'created_by'    => 1,
                        ],
                        [
                            'title'         => 'B2',
                            'path'          => '/B2',
                            'component'     => 'b2',
                            'type'          => 'b2',
                            'redirect'      => 'b2',
                            'icon'          => 'b2',
                            'state'         => 1,
                            'showNav'       => 1,
                            'ordering'      => 2,
                            'created_by'    => 1,
                        ],
                    ]
                ],
            ]
        ]);
    }


    public function migrate()
    {

    }
}
