<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use App\Models\TagTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group superadmins
 * @group roster
 */
class DeleteTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_delete_a_bookable_tag_team()
    {
        $this->actAs('super-administrator');
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertSoftDeleted('tag_teams', ['id' => $tagTeam->id]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_retired_tag_team()
    {
        $this->actAs('super-administrator');
        $tagTeam = factory(TagTeam::class)->states('retired')->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertSoftDeleted('tag_teams', ['id' => $tagTeam->id]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_pending_employment_tag_team()
    {
        $this->actAs('super-administrator');
        $tagTeam = factory(TagTeam::class)->states('pending-employment')->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertSoftDeleted('tag_teams', ['id' => $tagTeam->id]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_suspended_tag_team()
    {
        $this->actAs('super-administrator');
        $tagTeam = factory(TagTeam::class)->states('suspended')->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertSoftDeleted('tag_teams', ['id' => $tagTeam->id]);
    }
}
