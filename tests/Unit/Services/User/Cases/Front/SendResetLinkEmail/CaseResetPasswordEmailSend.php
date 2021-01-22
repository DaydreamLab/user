<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\SendResetLinkEmail;

use DaydreamLab\User\Models\Password\PasswordReset;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Notifications\ResetPasswordNotification;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class CaseResetPasswordEmailSend extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        Notification::fake();
        $user = User::factory()->create();
        $input = collect([
            'email' => $user->email
        ]);
        $this->repo
            ->shouldReceive('findBy')
            ->andReturn(collect([$user]));

        $this->passwordResetService
            ->shouldReceive('add')
            ->andReturn(PasswordReset::factory()->create());

        $this->service->sendResetLinkEmail($input);

        $this->assertEquals('ResetPasswordEmailSend', $this->service->status);
        Notification::assertSentTo([$user], ResetPasswordNotification::class);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}