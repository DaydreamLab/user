<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Base\Login;

use DaydreamLab\User\Tests\Unit\Services\User\Cases\Base\UserTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseLoginSuccess extends UserTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $input = collect([
            'email' => 'admin@daydream-lab.com',
        ]);

        // 測試一般登入
        $this->service->login($input);
        $this->assertEquals('LoginSuccess',$this->service->status);
        $this->assertObjectHasAttribute('token', $this->service->response);

        // 測試多重登入
        $this->service->login($input);
        $this->assertEquals('MultipleLoginSuccess',$this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}