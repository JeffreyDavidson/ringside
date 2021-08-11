<?php

namespace Tests\Unit\Strategies\Unretire;

use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ManagerUnretireStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_retired_manager_can_be_unretired()
    {
        $manager = Manager::factory()->retired()->create();

        $manager->unretire();

        $this->assertEquals('available', $manager->status);
        $this->assertNotNull($manager->previousRetirement->ended_at);
    }

    /**
     * @test
     */
    public function a_future_employment_manager_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $manager = Manager::factory()->withFutureEmployment()->create();

        $manager->unretire();
    }

    /**
     * @test
     */
    public function a_suspended_manager_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $manager = Manager::factory()->suspended()->create();

        $manager->unretire();
    }

    /**
     * @test
     */
    public function an_injured_manager_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $manager = Manager::factory()->suspended()->create();

        $manager->unretire();
    }

    /**
     * @test
     */
    public function an_available_manager_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $manager = Manager::factory()->available()->create();

        $manager->unretire();
    }

    /**
     * @test
     */
    public function a_manager_that_retires_and_unretires_has_a_previous_retirement()
    {
        $manager = Manager::factory()->available()->create();
        $manager->retire();
        $manager->unretire();

        $this->assertCount(1, $manager->previousRetirements);
    }
}
