<?php

namespace Tests\Integration\Models;

use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group referees
 */
class RefereeIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_bookable_referees()
    {
        $futureEmployedReferee = Referee::factory()->withFutureEmployment()->create();
        $bookableReferee = Referee::factory()->bookable()->create();
        $injuredReferee = Referee::factory()->injured()->create();
        $suspendedReferee = Referee::factory()->suspended()->create();
        $retiredReferee = Referee::factory()->retired()->create();
        $releasedReferee = Referee::factory()->released()->create();

        $bookableReferees = Referee::bookable()->get();

        $this->assertCount(1, $bookableReferees);
        $this->assertTrue($bookableReferees->contains($bookableReferee));
        $this->assertFalse($bookableReferees->contains($futureEmployedReferee));
        $this->assertFalse($bookableReferees->contains($injuredReferee));
        $this->assertFalse($bookableReferees->contains($suspendedReferee));
        $this->assertFalse($bookableReferees->contains($retiredReferee));
        $this->assertFalse($bookableReferees->contains($releasedReferee));
    }

    /**
     * @test
     */
    public function it_can_get_future_employed_referees()
    {
        $futureEmployedReferee = Referee::factory()->withFutureEmployment()->create();
        $bookableReferee = Referee::factory()->bookable()->create();
        $injuredReferee = Referee::factory()->injured()->create();
        $suspendedReferee = Referee::factory()->suspended()->create();
        $retiredReferee = Referee::factory()->retired()->create();
        $releasedReferee = Referee::factory()->released()->create();

        $futureEmployedReferees = Referee::futureEmployed()->get();

        $this->assertCount(1, $futureEmployedReferees);
        $this->assertTrue($futureEmployedReferees->contains($futureEmployedReferee));
        $this->assertFalse($futureEmployedReferees->contains($bookableReferee));
        $this->assertFalse($futureEmployedReferees->contains($injuredReferee));
        $this->assertFalse($futureEmployedReferees->contains($suspendedReferee));
        $this->assertFalse($futureEmployedReferees->contains($retiredReferee));
        $this->assertFalse($futureEmployedReferees->contains($releasedReferee));
    }

    /**
     * @test
     */
    public function it_can_get_employed_referees()
    {
        $futureEmployedReferee = Referee::factory()->withFutureEmployment()->create();
        $bookableReferee = Referee::factory()->bookable()->create();
        $injuredReferee = Referee::factory()->injured()->create();
        $suspendedReferee = Referee::factory()->suspended()->create();
        $retiredReferee = Referee::factory()->retired()->create();
        $releasedReferee = Referee::factory()->released()->create();

        $employedReferees = Referee::employed()->get();

        $this->assertCount(3, $employedReferees);
        $this->assertTrue($employedReferees->contains($injuredReferee));
        $this->assertTrue($employedReferees->contains($bookableReferee));
        $this->assertTrue($employedReferees->contains($suspendedReferee));
        $this->assertFalse($employedReferees->contains($futureEmployedReferee));
        $this->assertFalse($employedReferees->contains($retiredReferee));
        $this->assertFalse($employedReferees->contains($releasedReferee));
    }

    /**
     * @test
     */
    public function it_can_get_released_referees()
    {
        $futureEmployedReferee = Referee::factory()->withFutureEmployment()->create();
        $bookableReferee = Referee::factory()->bookable()->create();
        $injuredReferee = Referee::factory()->injured()->create();
        $suspendedReferee = Referee::factory()->suspended()->create();
        $retiredReferee = Referee::factory()->retired()->create();
        $releasedReferee = Referee::factory()->released()->create();

        $releasedReferees = Referee::released()->get();

        $this->assertCount(1, $releasedReferees);
        $this->assertTrue($releasedReferees->contains($releasedReferee));
        $this->assertFalse($releasedReferees->contains($futureEmployedReferee));
        $this->assertFalse($releasedReferees->contains($bookableReferee));
        $this->assertFalse($releasedReferees->contains($injuredReferee));
        $this->assertFalse($releasedReferees->contains($suspendedReferee));
        $this->assertFalse($releasedReferees->contains($retiredReferee));
    }

    /**
     * @test
     */
    public function it_can_get_suspended_referees()
    {
        $suspendedReferee = Referee::factory()->suspended()->create();
        $futureEmployedReferee = Referee::factory()->withFutureEmployment()->create();
        $bookableReferee = Referee::factory()->bookable()->create();
        $injuredReferee = Referee::factory()->injured()->create();
        $retiredReferee = Referee::factory()->retired()->create();
        $releasedReferee = Referee::factory()->released()->create();

        $suspendedReferees = Referee::suspended()->get();

        $this->assertCount(1, $suspendedReferees);
        $this->assertTrue($suspendedReferees->contains($suspendedReferee));
        $this->assertFalse($suspendedReferees->contains($futureEmployedReferee));
        $this->assertFalse($suspendedReferees->contains($bookableReferee));
        $this->assertFalse($suspendedReferees->contains($injuredReferee));
        $this->assertFalse($suspendedReferees->contains($retiredReferee));
        $this->assertFalse($suspendedReferees->contains($releasedReferee));
    }

    /**
     * @test
     */
    public function it_can_get_injured_referees()
    {
        $injuredReferee = Referee::factory()->injured()->create();
        $futureEmployedReferee = Referee::factory()->withFutureEmployment()->create();
        $bookableReferee = Referee::factory()->bookable()->create();
        $suspendedReferee = Referee::factory()->suspended()->create();
        $retiredReferee = Referee::factory()->retired()->create();
        $releasedReferee = Referee::factory()->released()->create();

        $injuredReferees = Referee::injured()->get();

        $this->assertCount(1, $injuredReferees);
        $this->assertTrue($injuredReferees->contains($injuredReferee));
        $this->assertFalse($injuredReferees->contains($futureEmployedReferee));
        $this->assertFalse($injuredReferees->contains($bookableReferee));
        $this->assertFalse($injuredReferees->contains($suspendedReferee));
        $this->assertFalse($injuredReferees->contains($retiredReferee));
        $this->assertFalse($injuredReferees->contains($releasedReferee));
    }

    /**
     * @test
     */
    public function it_can_get_retired_referees()
    {
        $retiredReferee = Referee::factory()->retired()->create();
        $futureEmployedReferee = Referee::factory()->withFutureEmployment()->create();
        $bookableReferee = Referee::factory()->bookable()->create();
        $injuredReferee = Referee::factory()->injured()->create();
        $suspendedReferee = Referee::factory()->suspended()->create();

        $retiredReferees = Referee::retired()->get();

        $this->assertCount(1, $retiredReferees);
        $this->assertTrue($retiredReferees->contains($retiredReferee));
        $this->assertFalse($retiredReferees->contains($futureEmployedReferee));
        $this->assertFalse($retiredReferees->contains($bookableReferee));
        $this->assertFalse($retiredReferees->contains($injuredReferee));
        $this->assertFalse($retiredReferees->contains($suspendedReferee));
    }
}
