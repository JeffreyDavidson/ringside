<?php

namespace Tests\Unit\Strategies\Reinstate;

use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ManagerReinstateStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_available_manager_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $manager = Manager::factory()->available()->create();

        $manager->reinstate();
    }

    /**
     * @test
     */
    public function a_future_employment_manager_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $manager = Manager::factory()->withFutureEmployment()->create();

        $manager->reinstate();
    }

    /**
     * @test
     */
    public function an_injured_manager_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $manager = Manager::factory()->injured()->create();

        $manager->reinstate();
    }

    /**
     * @test
     */
    public function a_retired_manager_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $manager = Manager::factory()->retired()->create();

        $manager->reinstate();
    }

    /**
     * @test
     */
    public function a_suspended_manager_can_be_reinstated()
    {
        $manager = Manager::factory()->suspended()->create();

        $manager->reinstate();

        $this->assertEquals('available', $manager->status);
        $this->assertNotNull($manager->previousSuspension->ended_at);
    }
}
