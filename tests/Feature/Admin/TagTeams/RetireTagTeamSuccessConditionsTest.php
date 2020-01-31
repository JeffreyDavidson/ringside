<?php

namespace Tests\Feature\Admin\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group admins
 * @group roster
 */
class RetireTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_retire_a_bookable_tag_team()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->currentRetirement->started_at);
    }

    /** @test */
    public function an_administrator_can_retire_a_suspended_tag_team()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('suspended')->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->currentRetirement->started_at);
    }
}
