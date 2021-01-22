<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Front\Activate;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Front\UserFrontTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class CaseHasBeenActivated extends UserFrontTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $user = User::factory()->create(['activation' => 1]);
        $this->repo
            ->shouldReceive('findBy')
            ->andReturn(collect([$user]));
        $this->assertHttpResponseException('activate', $user->activate_token, 'HasBeenActivated');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}