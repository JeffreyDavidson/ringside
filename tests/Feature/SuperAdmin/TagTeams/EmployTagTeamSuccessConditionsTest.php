<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group superadmins
 * @group roster
 */
class EmployTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_employ_a_pending_employment_tag_team()
    {
        $this->actAs('super-administrator');
        $tagTeam = factory(TagTeam::class)->states('employable', 'pending-employment')->create();

        $response = $this->employRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        tap($tagTeam->fresh(), function ($tagTeam) {
            $this->assertTrue($tagTeam->is_employed);
        });
    }
}
