<?php

namespace DaydreamLab\User\tests\Unit\Services\UserTag\Cases\Admin\Apply;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserTag;
use DaydreamLab\User\Tests\Unit\Services\UserTag\Cases\Admin\UserTagAdminTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseInvalidDeleteIds extends UserTagAdminTestBase
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
            'getDeleteQuery' => collect(),
            'addIds'    => [],
            'deleteIds' => [1]
        ]);

        $this->repo
            ->shouldReceive('search')
            ->andReturn(collect([]));
        $this->repo
            ->shouldReceive('getModel')
            ->andReturn(UserTag::factory()->create());

        $this->assertException('apply', $input, 'InvalidApplyDeleteIds');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
