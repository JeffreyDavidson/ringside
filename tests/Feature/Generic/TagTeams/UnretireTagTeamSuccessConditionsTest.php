<?php

namespace Tests\Feature\Generic\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group generics
 * @group roster
 */
class UnretireTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unretiring_a_tag_team_makes_both_wrestlers_bookable()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('employable', 'retired')->create();

        $response = $this->unretireRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertCount(2, $tagTeam->currentWrestlers->filter->is_bookable);
    }
}
