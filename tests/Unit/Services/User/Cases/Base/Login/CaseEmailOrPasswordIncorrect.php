<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Base\Login;

use DaydreamLab\User\Tests\Unit\Services\User\Cases\Base\UserTestBase;
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

        $this->assertException('login', $input, 'EmailOrPasswordIncorrect');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}