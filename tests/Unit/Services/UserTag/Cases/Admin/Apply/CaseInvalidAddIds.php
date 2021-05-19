<?php

namespace DaydreamLab\User\tests\Unit\Services\UserTas\Cases\Admin\Apply;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserTag;
use DaydreamLab\User\Tests\Unit\Services\UserTag\Cases\Admin\UserTagAdminTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseInvalidAddIds extends UserTagAdminTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $input = collect([
            'getAddQuery' => collect(),
            'addIds' => [1]
        ]);

        $this->repo
            ->shouldReceive('search')
            ->andReturn(collect());
        $this->repo
            ->shouldReceive('getModel')
            ->andReturn(UserTag::factory()->create());

        $this->assertException('apply', $input, 'InvalidApplyAddIds');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}