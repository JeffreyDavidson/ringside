<?php

namespace Tests\Unit\Strategies\Suspension;

use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ManagerSuspensionStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_available_manager_can_be_suspended()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = Manager::factory()->available()->create();

        $manager->suspend();

        $this->assertEquals('suspended', $manager->status);
        $this->assertCount(1, $manager->suspensions);
        $this->assertNull($manager->currentSuspension->ended_at);
        $this->assertEquals($now->toDateTimeString(), $manager->currentSuspension->started_at);
    }

    /**
     * @test
     */
    public function a_future_employment_manager_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $manager = Manager::factory()->withFutureEmployment()->create();

        $manager->suspend();
    }

    /**
     * @test
     */
    public function a_suspended_manager_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $manager = Manager::factory()->suspended()->create();

        $manager->suspend();
    }

    /**
     * @test
     */
    public function a_retired_manager_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $manager = Manager::factory()->retired()->create();

        $manager->suspend();
    }

    /**
     * @test
     */
    public function an_suspended_manager_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $manager = Manager::factory()->suspended()->create();

        $manager->suspend();
    }
}
