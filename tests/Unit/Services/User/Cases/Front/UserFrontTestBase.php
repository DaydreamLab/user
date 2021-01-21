<?php

namespace DaydreamLab\User\Tests\Unit\Services\User\Cases\Front;

use DaydreamLab\JJAJ\Tests\BaseTest;
use DaydreamLab\User\Repositories\User\Front\UserFrontRepository;
use DaydreamLab\User\Services\Password\Front\PasswordResetFrontService;
use DaydreamLab\User\Services\Social\SocialUserService;
use DaydreamLab\User\Services\User\Front\UserFrontService;
use Illuminate\Support\Facades\Artisan;
use Mockery;

class UserFrontTestBase extends BaseTest
{
    protected $service;

    protected $repo;

    protected $socialUserService;

    protected $passwordResetService;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = Mockery::mock(UserFrontRepository::class);
        $this->socialUserService = Mockery::mock(SocialUserService::class);
        $this->passwordResetService = Mockery::mock(PasswordResetFrontService::class);

        $this->service = new UserFrontService(
            $this->repo,
            $this->socialUserService,
            $this->passwordResetService
        );
        Artisan::call('passport:install');
        Artisan::call('user:seed');
    }
    

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}