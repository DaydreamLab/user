<?php

namespace DaydreamLab\User\tests\Unit\Services\UserTas\Cases\Admin\Apply;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserTag;
use DaydreamLab\User\Tests\Unit\Services\UserTag\Cases\Admin\UserTagAdminTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseApplyTagSuccess extends UserTagAdminTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $tag1 = UserTag::factory()->create();
        $tag2 = UserTag::factory()->create();
        $user = User::factory()->create();

        $input = collect([
            'getAddQuery' => collect([]),
            'getDeleteQuery' => collect(),
            'addIds'    => [$tag1->id, $tag2->id],
            'deleteIds' => [$tag2->id]
        ]);

        $this->repo
            ->shouldReceive('search')
            ->once()
            ->andReturn(collect([$tag1, $tag2]));
        $this->repo
            ->shouldReceive('getModel')
            ->andReturn(UserTag::factory()->make());

        $this->repo
            ->shouldReceive('search')
            ->once()
            ->andReturn(collect([$tag2]));
        $this->userRepo->shouldReceive('getModel')->andReturn(User::factory()->make());
        $this->userRepo->shouldReceive('search')->andReturn(User::all());

        $this->service->apply($input);
        $this->assertEquals('ApplyTagsSuccess', $this->service->status);
        $this->assertEquals(1, $user->refresh()->tags->count());
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}