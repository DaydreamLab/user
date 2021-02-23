<?php

namespace DaydreamLab\User\Tests\Unit\Services\UserTag\Cases\Admin;

use DaydreamLab\JJAJ\Tests\BaseTest;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Repositories\User\Admin\UserTagAdminRepository;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use DaydreamLab\User\Services\User\Admin\UserTagAdminService;
use Illuminate\Support\Facades\Artisan;
use Mockery;

class UserTagAdminTestBase extends BaseTest
{
    protected $package = 'User';

    protected $modelName = 'UserTag';

    protected $modelType = 'Admin';

    protected $service;

    protected $repo;

    protected $userRepo;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = Mockery::mock(UserTagAdminRepository::class);

        $this->userRepo = Mockery::mock(UserAdminRepository::class);
        $userService = new UserAdminService($this->userRepo);

        $this->service = new UserTagAdminService($this->repo , $userService);

        Artisan::call('passport:install');
        Artisan::call('user:seed');
    }
    

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}