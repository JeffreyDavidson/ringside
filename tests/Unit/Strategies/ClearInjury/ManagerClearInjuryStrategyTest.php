<?php

namespace Tests\Unit\Strategies\ClearInjury;

use App\Enums\ManagerStatus;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ManagerClearInjuryStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_available_manager_cannot_be_cleared_from_an_injury()
    {
        $this->expectException(CannotBeClearedFromInjuryException::class);

        $manager = Manager::factory()->available()->create();

        $manager->clearFromInjury();
    }

    /**
     * @test
     */
    public function a_future_employment_manager_cannot_be_cleared_from_an_injury()
    {
        $this->expectException(CannotBeClearedFromInjuryException::class);

        $manager = Manager::factory()->withFutureEmployment()->create();

        $manager->clearFromInjury();
    }

    /**
     * @test
     */
    public function a_suspended_manager_cannot_be_cleared_from_an_injury()
    {
        $this->expectException(CannotBeClearedFromInjuryException::class);

        $manager = Manager::factory()->suspended()->create();

        $manager->clearFromInjury();
    }

    /**
     * @test
     */
    public function a_retired_manager_cannot_be_cleared_from_injury()
    {
        $this->expectException(CannotBeClearedFromInjuryException::class);

        $manager = Manager::factory()->retired()->create();

        $manager->clearFromInjury();
    }

    /**
     * @test
     */
    public function an_injured_manager_can_be_cleared_from_an_injury()
    {
        $manager = Manager::factory()->injured()->create();

        $manager->clearFromInjury();

        $this->assertEquals(ManagerStatus::AVAILABLE, $manager->status);
        $this->assertNotNull($manager->previousInjury->ended_at);
    }
}
