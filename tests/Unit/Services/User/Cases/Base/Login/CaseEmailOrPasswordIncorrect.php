<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Base\Login;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\UserTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseEmailOrPasswordIncorrect extends UserTestBase
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
            'password' => 'daydream5181'
        ]);

        $this->service->login($input);
        $this->assertEquals('EmailOrPasswordIncorrect',$this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}