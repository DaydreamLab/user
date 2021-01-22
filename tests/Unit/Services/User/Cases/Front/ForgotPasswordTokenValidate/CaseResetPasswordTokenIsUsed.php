<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\ForgotPasswordTokenValidate;

use DaydreamLab\User\Models\Password\PasswordReset;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class CaseResetPasswordTokenIsUsed extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

    }


    public function testCase()
    {
        $passwordReset = PasswordReset::factory()->make(['reset_at' => now()->toDateTimeString()]);
        $this->passwordResetService
            ->shouldReceive('findBy')
            ->andReturn(collect([$passwordReset]));

        $this->assertHttpResponseException('forgotPasswordTokenValidate', $passwordReset->token, 'ResetPasswordTokenIsUsed');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}