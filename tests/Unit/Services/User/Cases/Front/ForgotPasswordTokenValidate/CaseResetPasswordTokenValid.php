<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\ForgotPasswordTokenValidate;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Password\PasswordReset;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseResetPasswordTokenValid extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $passwordReset = PasswordReset::factory()->make();
        $this->passwordResetService
            ->shouldReceive('findBy')
            ->andReturn(collect([$passwordReset]));
        $this->service->forgotPasswordTokenValidate($passwordReset->token);
        $this->assertEquals('ResetPasswordTokenValid', $this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}