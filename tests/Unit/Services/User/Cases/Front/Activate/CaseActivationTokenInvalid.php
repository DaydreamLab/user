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
        $this->repo
            ->shouldReceive('findBy')
            ->andReturn(collect());

        $this->assertException('activate', Str::random(), 'ActivationTokenInvalid');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}