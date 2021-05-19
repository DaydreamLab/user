<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\ForgotPasswordTokenValidate;

use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class CaseResetPasswordTokenInvalid extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $this->passwordResetService
            ->shouldReceive('findBy')
            ->andReturn(collect());

        $this->assertException('forgotPasswordTokenValidate', Str::random(), 'ResetPasswordTokenInvalid');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}