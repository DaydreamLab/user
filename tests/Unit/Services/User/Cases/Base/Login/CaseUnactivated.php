<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Base\Login;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Base\UserTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseUnactivated extends UserTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

    }


    public function testCase()
    {
        $user = User::all()->first();
        $user->activation = 0;
        $user->save();

        $input = collect([
            'email' => 'admin@daydream-lab.com',
            'password' => 'daydream5182'
        ]);

        $this->assertHttpResponseException('login', $input, 'Unactivated');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}