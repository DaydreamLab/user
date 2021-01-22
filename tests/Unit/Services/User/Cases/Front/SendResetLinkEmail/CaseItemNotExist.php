<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\SendResetLinkEmail;

use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseItemNotExist extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $input = collect([
            'email' => $this->faker->email
        ]);

        $this->repo
            ->shouldReceive('findBy')
            ->andReturn(collect());

        $this->assertHttpResponseException('sendResetLinkEmail', $input, 'ItemNotExist');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}