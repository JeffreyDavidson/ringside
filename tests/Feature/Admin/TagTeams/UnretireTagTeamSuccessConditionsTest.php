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
class UnretireTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_unretire_a_retired_tag_team()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('retired')->create();

        $response = $this->unretireRequest($tagTeam);
    
        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->retirements()->latest()->first()->ended_at);
    }
}
