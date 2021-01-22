<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\Register;

use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class CaseRegistrationIsBlocked extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        Config::set('daydreamlab.user.register.enable', 0);
        $this->assertHttpResponseException('register', collect(), 'RegistrationIsBlocked');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}