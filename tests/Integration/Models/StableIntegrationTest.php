<?php

namespace Tests\Integration\Models;

use App\Models\Stable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group stables
 */
class StableIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_active_stables()
    {
        $activeStable = Stable::factory()->active()->create();
        $futureActivatedStable = Stable::factory()->withFutureActivation()->create();
        $retiredStable = Stable::factory()->retired()->create();

        $activeStables = Stable::active()->get();

        $this->assertCount(1, $activeStables);
        $this->assertTrue($activeStables->contains($activeStable));
        $this->assertFalse($activeStables->contains($futureActivatedStable));
        $this->assertFalse($activeStables->contains($retiredStable));
    }

    /**
     * @test
     */
    public function it_can_get_future_activated_stables()
    {
        $activeStable = Stable::factory()->active()->create();
        $futureActivatedStable = Stable::factory()->withFutureActivation()->create();
        $retiredStable = Stable::factory()->retired()->create();

        $futureActiveStables = Stable::withFutureActivation()->get();

        $this->assertCount(1, $futureActiveStables);
        $this->assertTrue($futureActiveStables->contains($futureActivatedStable));
        $this->assertFalse($futureActiveStables->contains($activeStable));
        $this->assertFalse($futureActiveStables->contains($retiredStable));
    }

    /**
     * @test
     */
    public function it_can_get_retired_stables()
    {
        $activeStable = Stable::factory()->active()->create();
        $futureActivatedStable = Stable::factory()->withFutureActivation()->create();
        $retiredStable = Stable::factory()->retired()->create();

        $retiredStables = Stable::retired()->get();

        $this->assertCount(1, $retiredStables);
        $this->assertTrue($retiredStables->contains($retiredStable));
        $this->assertFalse($retiredStables->contains($activeStable));
        $this->assertFalse($retiredStables->contains($futureActivatedStable));
    }
}
