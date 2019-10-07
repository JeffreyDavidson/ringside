<?php

namespace Tests\Unit\Observers;

use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group roster
 */
class TagTeamObserverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_tag_team_has_a_default_status_of_pending_employment()
    {
        $tagTeam = factory(TagTeam::class)->create();

        $this->assertEquals('pending-employment', $tagTeam->status);
    }

    /** @test */
    public function an_employed_tag_team_with_a_current_retirement_has_a_status_of_retired()
    {
        $tagTeam = factory(TagTeam::class)->states('retired')->create();

        $this->assertEquals('retired', $tagTeam->status);
    }

    /** @test */
    public function a_tag_team_with_a_current_suspension_has_a_status_of_suspended()
    {
        $tagTeam = factory(TagTeam::class)->states('suspended')->create();

        $this->assertEquals('suspended', $tagTeam->status);
    }

    /** @test */
    public function a_tag_team_with_a_current_injury_has_a_status_of_injured()
    {
        $tagTeam = factory(TagTeam::class)->states('injured')->create();

        $this->assertEquals('injured', $tagTeam->status);
    }

    /** @test */
    public function a_tag_team_employed_at_the_current_time_or_in_the_past_without_being_currently_injured_or_retired_or_suspended_has_a_status_of_bookable()
    {
        $tagTeam = factory(TagTeam::class)->states('employed')->create();

        $this->assertEquals('bookable', $tagTeam->status);
    }
}
