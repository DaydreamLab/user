<?php

namespace DaydreamLab\User\Tests\Unit\Services\User;

use DaydreamLab\JJAJ\Tests\BaseTest;
use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Mockery;

class UserTestBase extends BaseTest
{
    protected $service;

    protected $repo;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = Mockery::mock(UserRepository::class);
        $this->service = $this->app->make(UserService::class);
        Artisan::call('passport:install');
        Artisan::call('user:seed');
    }
    

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}