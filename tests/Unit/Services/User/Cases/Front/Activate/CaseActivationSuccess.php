<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\Activate;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseActivationSuccess extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $user = User::all()->first();
        $token = $user->activate_token;
        $user->activation = 0;
        $user->save();

        $this->service->activate($token);
        $this->assertEquals('ActivationSuccess',$this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}