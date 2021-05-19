<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\ResetPassword;

use DaydreamLab\User\Models\Password\PasswordReset;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class CaseResetPasswordSuccess extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $user = User::factory()->create();
        $passwordReset = PasswordReset::factory()->create(['email' => $user->email]);
        $this->passwordResetService
            ->shouldReceive('findBy')
            ->andReturn(collect([$passwordReset]));

        $this->repo
            ->shouldReceive('findBy')
            ->andReturn(collect([$user]));

        $this->repo
            ->shouldReceive('update')
            ->andReturn(true);

        $this->passwordResetService
            ->shouldReceive('update')
            ->andReturn(true);

        $this->repo
            ->shouldReceive('modify')
            ->andReturn(true);

        $input = collect([
            'token'     => $passwordReset->token,
            'password'  => Str::random()
        ]);

        $this->service->resetPassword($input);
        $this->assertEquals('ResetPasswordSuccess', $this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}