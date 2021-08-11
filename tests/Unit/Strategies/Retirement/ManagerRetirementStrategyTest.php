<?php

namespace Tests\Unit\Strategies\Retirement;

use App\Models\Manager;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ManagerRetirementStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_available_manager_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = Manager::factory()->available()->create();

        $manager->retire();

        $this->assertEquals('retired', $manager->status);
        $this->assertCount(1, $manager->retirements);
        $this->assertEquals($now->toDateTimeString(), $manager->currentRetirement->started_at);
    }

    /**
     * @test
     */
    public function a_suspended_manager_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = Manager::factory()->suspended()->create();

        $this->assertNull($manager->currentSuspension->ended_at);

        $manager->retire();

        $this->assertEquals('retired', $manager->status);
        $this->assertCount(1, $manager->retirements);
        $this->assertNotNull($manager->previousSuspension->ended_at);
        $this->assertEquals($now->toDateTimeString(), $manager->currentRetirement->started_at);
    }

    /**
     * @test
     */
    public function an_injured_manager_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = Manager::factory()->injured()->create();

        $this->assertNull($manager->injuries()->latest()->first()->ended_at);

        $manager->retire();

        $this->assertEquals('retired', $manager->status);
        $this->assertCount(1, $manager->retirements);
        $this->assertNotNull($manager->previousInjury->ended_at);
        $this->assertEquals($now->toDateTimeString(), $manager->currentRetirement->started_at);
    }

    /**
     * @test
     */
    public function a_future_employment_manager_cannot_be_retired()
    {
        $this->expectException(CannotBeRetiredException::class);

        $manager = Manager::factory()->withFutureEmployment()->create();

        $manager->retire();
    }

    /**
     * @test
     */
    public function a_retired_manager_cannot_be_retired()
    {
        $this->expectException(CannotBeRetiredException::class);

        $manager = Manager::factory()->retired()->create();

        $manager->retire();
    }
}
