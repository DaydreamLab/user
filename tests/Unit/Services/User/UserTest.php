<?php

namespace DaydreamLab\User\Tests\Unit\Services;

use DaydreamLab\JJAJ\Tests\BaseTest;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Repositories\User\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class UserTest extends BaseTest
{
    use RefreshDatabase;

    protected $service;

    protected $repo;

    protected $user;


    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = Mockery::mock(UserRepository::class);
        // Get user
        $this->user = User::find(1);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}