<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\Register;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Notifications\RegisteredNotification;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CaseRegisterSuccess extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        Config::set('daydreamlab.user.register.enable', 1);

        $this->repo
            ->shouldReceive('findBy')
            ->andReturn(collect());

        $input = collect([
            'email' => $this->faker->email,
            'password' => Str::random()
        ]);
        Notification::fake();
        $user = User::factory()->create();
        $this->repo
            ->shouldReceive('add')
            ->andReturn($user);
        $this->service->register($input);
        $this->assertEquals('RegisterSuccess',$this->service->status);
        Notification::assertSentTo([$user], RegisteredNotification::class);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}