<?php

namespace Tests\Integration\Models;

use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group tagteams
 */
class TagTeamTest extends TestCase
{
    use RefreshDatabase;

    private $futureEmployedTagTeam;
    private $bookableTagTeam;
    private $suspendedTagTeam;
    private $retiredTagTeam;
    private $releasedTagTeam;

    public function setUp(): void
    {
        parent::setUp();

        $this->futureEmployedTagTeam = TagTeam::factory()->withFutureEmployment()->create();
        $this->bookableTagTeam = TagTeam::factory()->bookable()->create();
        $this->suspendedTagTeam = TagTeam::factory()->suspended()->create();
        $this->retiredTagTeam = TagTeam::factory()->retired()->create();
        $this->releasedTagTeam = TagTeam::factory()->released()->create();
    }

    /**
     * @test
     */
    public function it_can_get_bookable_tag_teams()
    {
        $bookableTagTeams = TagTeam::bookable()->get();

        $this->assertCount(1, $bookableTagTeams);
        $this->assertTrue($bookableTagTeams->contains($this->bookableTagTeam));
        $this->assertFalse($bookableTagTeams->contains($this->futureEmployedTagTeam));
        $this->assertFalse($bookableTagTeams->contains($this->suspendedTagTeam));
        $this->assertFalse($bookableTagTeams->contains($this->retiredTagTeam));
        $this->assertFalse($bookableTagTeams->contains($this->releasedTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_future_employed_tag_teams()
    {
        $futureEmployedTagTeams = TagTeam::futureEmployed()->get();

        $this->assertCount(1, $futureEmployedTagTeams);
        $this->assertTrue($futureEmployedTagTeams->contains($this->futureEmployedTagTeam));
        $this->assertFalse($futureEmployedTagTeams->contains($this->bookableTagTeam));
        $this->assertFalse($futureEmployedTagTeams->contains($this->suspendedTagTeam));
        $this->assertFalse($futureEmployedTagTeams->contains($this->retiredTagTeam));
        $this->assertFalse($futureEmployedTagTeams->contains($this->releasedTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_employed_tag_teams()
    {
        $employedTagTeams = TagTeam::employed()->get();

        $this->assertCount(2, $employedTagTeams);
        $this->assertTrue($employedTagTeams->contains($this->bookableTagTeam));
        $this->assertTrue($employedTagTeams->contains($this->suspendedTagTeam));
        $this->assertFalse($employedTagTeams->contains($this->futureEmployedTagTeam));
        $this->assertFalse($employedTagTeams->contains($this->retiredTagTeam));
        $this->assertFalse($employedTagTeams->contains($this->releasedTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_released_tag_teams()
    {
        $releasedTagTeams = TagTeam::released()->get();

        $this->assertCount(1, $releasedTagTeams);
        $this->assertTrue($releasedTagTeams->contains($this->releasedTagTeam));
        $this->assertFalse($releasedTagTeams->contains($this->futureEmployedTagTeam));
        $this->assertFalse($releasedTagTeams->contains($this->bookableTagTeam));
        $this->assertFalse($releasedTagTeams->contains($this->suspendedTagTeam));
        $this->assertFalse($releasedTagTeams->contains($this->retiredTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_suspended_tag_teams()
    {
        $suspendedTagTeams = TagTeam::suspended()->get();

        $this->assertCount(1, $suspendedTagTeams);
        $this->assertTrue($suspendedTagTeams->contains($this->suspendedTagTeam));
        $this->assertFalse($suspendedTagTeams->contains($this->futureEmployedTagTeam));
        $this->assertFalse($suspendedTagTeams->contains($this->bookableTagTeam));
        $this->assertFalse($suspendedTagTeams->contains($this->retiredTagTeam));
        $this->assertFalse($suspendedTagTeams->contains($this->releasedTagTeam));
    }

    /**
     * @test
     */
    public function it_can_get_retired_tag_teams()
    {
        $retiredTagTeams = TagTeam::retired()->get();

        $this->assertCount(1, $retiredTagTeams);
        $this->assertTrue($retiredTagTeams->contains($this->retiredTagTeam));
        $this->assertFalse($retiredTagTeams->contains($this->futureEmployedTagTeam));
        $this->assertFalse($retiredTagTeams->contains($this->bookableTagTeam));
        $this->assertFalse($retiredTagTeams->contains($this->suspendedTagTeam));
        $this->assertFalse($retiredTagTeams->contains($this->releasedTagTeam));
    }
}
