<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Base\ChangePassword;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Base\UserTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class OldPasswordIncorrect extends UserTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $input = collect([
            'old_password' => Str::random(8),
            'password'     => 'daydream5182',
            'password_confirmation' => 'daydream5182'
        ]);

        $user = User::all()->first();

        $this->actingAs($user, 'api');
        $this->service->changePassword($input);
        $this->assertEquals('OldPasswordIncorrect',$this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}