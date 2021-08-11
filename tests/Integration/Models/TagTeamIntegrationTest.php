<?php

namespace Tests\Integration\Models;

use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group tagteams
 */
class TagTeamIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_bookable_tag_teams()
    {
        $futureEmployedTagTeam = TagTeam::factory()->withFutureEmployment()->create();
        $bookableTagTeam = TagTeam::factory()->bookable()->create();
        $suspendedTagTeam = TagTeam::factory()->suspended()->create();
        $retiredTagTeam = TagTeam::factory()->retired()->create();
        $releasedTagTeam = TagTeam::factory()->released()->create();

        $bookableTagTeams = TagTeam::bookable()->get();

        $this->assertCount(1, $bookableTagTeams);
        $this->assertTrue($bookableTagTeams->contains($bookableTagTeam));
        $this->assertFalse($bookableTagTeams->contains($futureEmployedTagTeam));
        $this->assertFalse($bookableTagTeams->contains($suspendedTagTeam));
        $this->assertFalse($bookableTagTeams->contains($retiredTagTeam));
        $this->assertFalse($bookableTagTeams->contains($releasedTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_future_employed_tag_teams()
    {
        $futureEmployedTagTeam = TagTeam::factory()->withFutureEmployment()->create();
        $bookableTagTeam = TagTeam::factory()->bookable()->create();
        $suspendedTagTeam = TagTeam::factory()->suspended()->create();
        $retiredTagTeam = TagTeam::factory()->retired()->create();
        $releasedTagTeam = TagTeam::factory()->released()->create();

        $futureEmployedTagTeams = TagTeam::futureEmployed()->get();

        $this->assertCount(1, $futureEmployedTagTeams);
        $this->assertTrue($futureEmployedTagTeams->contains($futureEmployedTagTeam));
        $this->assertFalse($futureEmployedTagTeams->contains($bookableTagTeam));
        $this->assertFalse($futureEmployedTagTeams->contains($suspendedTagTeam));
        $this->assertFalse($futureEmployedTagTeams->contains($retiredTagTeam));
        $this->assertFalse($futureEmployedTagTeams->contains($releasedTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_employed_tag_teams()
    {
        $futureEmployedTagTeam = TagTeam::factory()->withFutureEmployment()->create();
        $bookableTagTeam = TagTeam::factory()->bookable()->create();
        $suspendedTagTeam = TagTeam::factory()->suspended()->create();
        $retiredTagTeam = TagTeam::factory()->retired()->create();
        $releasedTagTeam = TagTeam::factory()->released()->create();

        $employedTagTeams = TagTeam::employed()->get();

        $this->assertCount(2, $employedTagTeams);
        $this->assertTrue($employedTagTeams->contains($bookableTagTeam));
        $this->assertTrue($employedTagTeams->contains($suspendedTagTeam));
        $this->assertFalse($employedTagTeams->contains($futureEmployedTagTeam));
        $this->assertFalse($employedTagTeams->contains($retiredTagTeam));
        $this->assertFalse($employedTagTeams->contains($releasedTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_released_tag_teams()
    {
        $futureEmployedTagTeam = TagTeam::factory()->withFutureEmployment()->create();
        $bookableTagTeam = TagTeam::factory()->bookable()->create();
        $suspendedTagTeam = TagTeam::factory()->suspended()->create();
        $retiredTagTeam = TagTeam::factory()->retired()->create();
        $releasedTagTeam = TagTeam::factory()->released()->create();

        $releasedTagTeams = TagTeam::released()->get();

        $this->assertCount(1, $releasedTagTeams);
        $this->assertTrue($releasedTagTeams->contains($releasedTagTeam));
        $this->assertFalse($releasedTagTeams->contains($futureEmployedTagTeam));
        $this->assertFalse($releasedTagTeams->contains($bookableTagTeam));
        $this->assertFalse($releasedTagTeams->contains($suspendedTagTeam));
        $this->assertFalse($releasedTagTeams->contains($retiredTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_suspended_tag_teams()
    {
        $suspendedTagTeam = TagTeam::factory()->suspended()->create();
        $futureEmployedTagTeam = TagTeam::factory()->withFutureEmployment()->create();
        $bookableTagTeam = TagTeam::factory()->bookable()->create();
        $retiredTagTeam = TagTeam::factory()->retired()->create();
        $releasedTagTeam = TagTeam::factory()->released()->create();

        $suspendedTagTeams = TagTeam::suspended()->get();

        $this->assertCount(1, $suspendedTagTeams);
        $this->assertTrue($suspendedTagTeams->contains($suspendedTagTeam));
        $this->assertFalse($suspendedTagTeams->contains($futureEmployedTagTeam));
        $this->assertFalse($suspendedTagTeams->contains($bookableTagTeam));
        $this->assertFalse($suspendedTagTeams->contains($retiredTagTeam));
        $this->assertFalse($suspendedTagTeams->contains($releasedTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_retired_tag_teams()
    {
        $retiredTagTeam = TagTeam::factory()->retired()->create();
        $futureEmployedTagTeam = TagTeam::factory()->withFutureEmployment()->create();
        $bookableTagTeam = TagTeam::factory()->bookable()->create();
        $suspendedTagTeam = TagTeam::factory()->suspended()->create();

        $retiredTagTeams = TagTeam::retired()->get();

        $this->assertCount(1, $retiredTagTeams);
        $this->assertTrue($retiredTagTeams->contains($retiredTagTeam));
        $this->assertFalse($retiredTagTeams->contains($futureEmployedTagTeam));
        $this->assertFalse($retiredTagTeams->contains($bookableTagTeam));
        $this->assertFalse($retiredTagTeams->contains($suspendedTagTeam));
    }
}
