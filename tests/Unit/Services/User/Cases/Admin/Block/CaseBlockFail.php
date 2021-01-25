<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Admin\Block;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Admin\UserAdminTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseBlockFail extends UserAdminTestBase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testCase()
    {
        $input = collect([
            'ids'   => [1],
            'block' => 1
        ]);

        $user = User::factory()->create(['block' => 0]);

        $this->repo
            ->shouldReceive('find')
            ->andReturn($user);

        $this->repo
            ->shouldReceive('update')
            ->andReturn(false);

        $this->service->block($input);
        $this->assertEquals('BlockFail', $this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}