<?php

namespace Tests\Unit\Strategies\Injury;

use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ManagerInjuryStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_available_manager_can_be_injured()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = Manager::factory()->available()->create();

        $manager->injure();

        $this->assertEquals('injured', $manager->status);
        $this->assertCount(1, $manager->injuries);
        $this->assertNull($manager->currentInjury->ended_at);
        $this->assertEquals($now->toDateTimeString(), $manager->currentInjury->started_at);
    }

    /**
     * @test
     */
    public function a_future_employment_manager_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $manager = Manager::factory()->withFutureEmployment()->create();

        $manager->injure();
    }

    /**
     * @test
     */
    public function a_suspended_manager_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $manager = Manager::factory()->suspended()->create();

        $manager->injure();
    }

    /**
     * @test
     */
    public function a_retired_manager_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $manager = Manager::factory()->retired()->create();

        $manager->injure();
    }

    /**
     * @test
     */
    public function an_injured_manager_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $manager = Manager::factory()->injured()->create();

        $manager->injure();
    }
}
