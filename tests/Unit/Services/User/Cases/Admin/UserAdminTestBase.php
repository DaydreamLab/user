<?php

namespace DaydreamLab\User\Tests\Unit\Services\User\Cases\Admin;

use DaydreamLab\JJAJ\Tests\BaseTest;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use Illuminate\Support\Facades\Artisan;
use Mockery;

class UserAdminTestBase extends BaseTest
{
    protected $package = 'User';

    protected $modelName = 'User';

    protected $modelType = 'Admin';

    protected $service;

    protected $repo;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = Mockery::mock(UserAdminRepository::class);

        $this->service = new UserAdminService($this->repo);
        Artisan::call('passport:install');
        Artisan::call('user:seed');
    }
    

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}