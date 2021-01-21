<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Base\ChangePassword;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\UserTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CaseChangePasswordSuccess extends UserTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $input = collect([
            'old_password' => 'daydream5182',
            'password'     => $password = Str::random(8),
        ]);

        // 更改密碼
        $user = User::all()->first();
        $this->actingAs($user, 'api');
        $this->service->changePassword($input);
        $this->assertEquals('ChangePasswordSuccess',$this->service->status);

        // 測試修改密碼後可以登入
        $input = collect([
            'email'     => 'admin@daydream-lab.com',
            'password'  => $password,
        ]);
        Auth::shouldUse('web');
        $this->service->login($input);
        $this->assertEquals('LoginSuccess',$this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}