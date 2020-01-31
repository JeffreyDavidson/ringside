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
class EmployTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_employ_a_pending_employment_tag_team_with_wrestlers()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('pending-employment')->create();

        $response = $this->employRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        tap($tagTeam->fresh(), function ($tagTeam) {
            $this->assertTrue($tagTeam->is_employed);
        });
    }
}
