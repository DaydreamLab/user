<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Base\CheckEmail;

use DaydreamLab\User\Tests\Unit\Services\User\Cases\Base\UserTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseEmailIsRegistered extends UserTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $email = 'admin@daydream-lab.com';

        $this->assertException('checkEmail', $email, 'EmailIsRegistered' );
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}