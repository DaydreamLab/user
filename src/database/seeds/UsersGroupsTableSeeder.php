<?php

namespace DaydreamLab\User\Database\Seeds;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\User\Admin\UserGroupAdmin;
use DaydreamLab\User\Repositories\User\Admin\UserGroupAdminRepository;
use DaydreamLab\User\Services\User\Admin\UserGroupAdminService;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use DaydreamLab\User\Repositories\Viewlevel\Admin\ViewlevelAdminRepository;
use DaydreamLab\User\Models\Viewlevel\Admin\ViewlevelAdmin;
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
        $viewlevelAdminService = new ViewlevelAdminService(new ViewlevelAdminRepository(new ViewlevelAdmin()));
        $service = new UserGroupAdminService(new UserGroupAdminRepository(new UserGroupAdmin()), $viewlevelAdminService);

        $root = UserGroupAdmin::create([
            'title'         => 'ROOT',
            'description'   => 'ROOT',
            'ordering'      => 1
        ]);

        $public = $service->store(Helper::collect([
            'parent_id'     => $root->id,
            'title'         => 'Public',
            'description'   => 'Public',
        ]));

        $guest = $service->store(Helper::collect([
            'parent_id'     => $root->id,
            'title'         => 'Guest',
            'description'   => 'Guest',
        ]));


        $registered = $service->store(Helper::collect([
            'parent_id'     => $root->id,
            'title'         => 'Registered',
            'description'   => 'Registered',
        ]));


        $administator = $service->store(Helper::collect([
            'parent_id'     => $root->id,
            'title'         => 'Administrator',
            'description'   => 'Administrator',
        ]));


        $superuser = $service->store(Helper::collect([
            'parent_id'     => $root->id,
            'title'         => 'Super User',
            'description'   => 'Super User',
        ]));



//        $root->appendNode($public);
//        $public->appendNode($guest);
//        $public->appendNode($registered);
//        $public->appendNode($administator);
//        $public->appendNode($superuser);

    }
}
