<?php

namespace Tests\Integration\Models;

use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 */
class WrestlerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_bookable_wrestlers()
    {
        $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create();
        $bookableWrestler = Wrestler::factory()->bookable()->create();
        $injuredWrestler = Wrestler::factory()->injured()->create();
        $suspendedWrestler = Wrestler::factory()->suspended()->create();
        $retiredWrestler = Wrestler::factory()->retired()->create();
        $releasedWrestler = Wrestler::factory()->released()->create();

        $bookableWrestlers = Wrestler::bookable()->get();

        $this->assertCount(1, $bookableWrestlers);
        $this->assertTrue($bookableWrestlers->contains($bookableWrestler));
        $this->assertFalse($bookableWrestlers->contains($futureEmployedWrestler));
        $this->assertFalse($bookableWrestlers->contains($injuredWrestler));
        $this->assertFalse($bookableWrestlers->contains($suspendedWrestler));
        $this->assertFalse($bookableWrestlers->contains($retiredWrestler));
        $this->assertFalse($bookableWrestlers->contains($releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_future_employed_wrestlers()
    {
        $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create();
        $bookableWrestler = Wrestler::factory()->bookable()->create();
        $injuredWrestler = Wrestler::factory()->injured()->create();
        $suspendedWrestler = Wrestler::factory()->suspended()->create();
        $retiredWrestler = Wrestler::factory()->retired()->create();
        $releasedWrestler = Wrestler::factory()->released()->create();

        $futureEmployedWrestlers = Wrestler::futureEmployed()->get();

        $this->assertCount(1, $futureEmployedWrestlers);
        $this->assertTrue($futureEmployedWrestlers->contains($futureEmployedWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($bookableWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($injuredWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($suspendedWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($retiredWrestler));
        $this->assertFalse($futureEmployedWrestlers->contains($releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_employed_wrestlers()
    {
        $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create();
        $bookableWrestler = Wrestler::factory()->bookable()->create();
        $injuredWrestler = Wrestler::factory()->injured()->create();
        $suspendedWrestler = Wrestler::factory()->suspended()->create();
        $retiredWrestler = Wrestler::factory()->retired()->create();
        $releasedWrestler = Wrestler::factory()->released()->create();

        $employedWrestlers = Wrestler::employed()->get();

        $this->assertCount(3, $employedWrestlers);
        $this->assertTrue($employedWrestlers->contains($injuredWrestler));
        $this->assertTrue($employedWrestlers->contains($bookableWrestler));
        $this->assertTrue($employedWrestlers->contains($suspendedWrestler));
        $this->assertFalse($employedWrestlers->contains($futureEmployedWrestler));
        $this->assertFalse($employedWrestlers->contains($retiredWrestler));
        $this->assertFalse($employedWrestlers->contains($releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_released_wrestlers()
    {
        $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create();
        $bookableWrestler = Wrestler::factory()->bookable()->create();
        $injuredWrestler = Wrestler::factory()->injured()->create();
        $suspendedWrestler = Wrestler::factory()->suspended()->create();
        $retiredWrestler = Wrestler::factory()->retired()->create();
        $releasedWrestler = Wrestler::factory()->released()->create();

        $releasedWrestlers = Wrestler::released()->get();

        $this->assertCount(1, $releasedWrestlers);
        $this->assertTrue($releasedWrestlers->contains($releasedWrestler));
        $this->assertFalse($releasedWrestlers->contains($futureEmployedWrestler));
        $this->assertFalse($releasedWrestlers->contains($bookableWrestler));
        $this->assertFalse($releasedWrestlers->contains($injuredWrestler));
        $this->assertFalse($releasedWrestlers->contains($suspendedWrestler));
        $this->assertFalse($releasedWrestlers->contains($retiredWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_suspended_wrestlers()
    {
        $suspendedWrestler = Wrestler::factory()->suspended()->create();
        $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create();
        $bookableWrestler = Wrestler::factory()->bookable()->create();
        $injuredWrestler = Wrestler::factory()->injured()->create();
        $retiredWrestler = Wrestler::factory()->retired()->create();
        $releasedWrestler = Wrestler::factory()->released()->create();

        $suspendedWrestlers = Wrestler::suspended()->get();

        $this->assertCount(1, $suspendedWrestlers);
        $this->assertTrue($suspendedWrestlers->contains($suspendedWrestler));
        $this->assertFalse($suspendedWrestlers->contains($futureEmployedWrestler));
        $this->assertFalse($suspendedWrestlers->contains($bookableWrestler));
        $this->assertFalse($suspendedWrestlers->contains($injuredWrestler));
        $this->assertFalse($suspendedWrestlers->contains($retiredWrestler));
        $this->assertFalse($suspendedWrestlers->contains($releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_injured_wrestlers()
    {
        $injuredWrestler = Wrestler::factory()->injured()->create();
        $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create();
        $bookableWrestler = Wrestler::factory()->bookable()->create();
        $suspendedWrestler = Wrestler::factory()->suspended()->create();
        $retiredWrestler = Wrestler::factory()->retired()->create();
        $releasedWrestler = Wrestler::factory()->released()->create();

        $injuredWrestlers = Wrestler::injured()->get();

        $this->assertCount(1, $injuredWrestlers);
        $this->assertTrue($injuredWrestlers->contains($injuredWrestler));
        $this->assertFalse($injuredWrestlers->contains($futureEmployedWrestler));
        $this->assertFalse($injuredWrestlers->contains($bookableWrestler));
        $this->assertFalse($injuredWrestlers->contains($suspendedWrestler));
        $this->assertFalse($injuredWrestlers->contains($retiredWrestler));
        $this->assertFalse($injuredWrestlers->contains($releasedWrestler));
    }

    /**
     * @test
     */
    public function it_can_get_retired_wrestlers()
    {
        $retiredWrestler = Wrestler::factory()->retired()->create();
        $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create();
        $bookableWrestler = Wrestler::factory()->bookable()->create();
        $injuredWrestler = Wrestler::factory()->injured()->create();
        $suspendedWrestler = Wrestler::factory()->suspended()->create();

        $retiredWrestlers = Wrestler::retired()->get();

        $this->assertCount(1, $retiredWrestlers);
        $this->assertTrue($retiredWrestlers->contains($retiredWrestler));
        $this->assertFalse($retiredWrestlers->contains($futureEmployedWrestler));
        $this->assertFalse($retiredWrestlers->contains($bookableWrestler));
        $this->assertFalse($retiredWrestlers->contains($injuredWrestler));
        $this->assertFalse($retiredWrestlers->contains($suspendedWrestler));
    }
}
