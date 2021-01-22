<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\ForgotPasswordTokenValidate;

use DaydreamLab\User\Models\Password\PasswordReset;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseResetPasswordTokenExpired extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $passwordReset = PasswordReset::factory()->make(['expired_at' => now()->subMinutes(15)->toDateTimeString()]);
        $this->passwordResetService
            ->shouldReceive('findBy')
            ->andReturn(collect([$passwordReset]));

        $this->assertHttpResponseException('forgotPasswordTokenValidate', $passwordReset->token, 'ResetPasswordTokenExpired');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}