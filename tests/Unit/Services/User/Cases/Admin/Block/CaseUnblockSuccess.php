<?php

namespace DaydreamLab\User\tests\Unit\Services\User\Cases\Admin\Block;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Tests\Unit\Services\User\Cases\Admin\UserAdminTestBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseUnblockSuccess extends UserAdminTestBase
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
            'block' => 0
        ]);

        $user = User::factory()->create(['block' => 0]);

        $this->repo
            ->shouldReceive('find')
            ->andReturn($user);

        $this->repo
            ->shouldReceive('modify')
            ->andReturn(true);

        $this->service->block($input);
        $this->assertEquals('UnblockSuccess', $this->service->status);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}