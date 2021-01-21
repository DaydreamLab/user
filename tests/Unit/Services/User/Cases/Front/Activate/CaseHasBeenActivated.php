<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\Activate;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseHasBeenActivated extends UserFrontTestBase
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

        $this->service->activate($token);
        $this->assertEquals('HasBeenActivated',$this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}