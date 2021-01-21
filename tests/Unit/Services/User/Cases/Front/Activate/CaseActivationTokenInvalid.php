<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\Activate;

use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class CaseActivationTokenInvalid extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $this->service->activate(Str::random(8));
        $this->assertEquals('ActivationTokenInvalid',$this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}