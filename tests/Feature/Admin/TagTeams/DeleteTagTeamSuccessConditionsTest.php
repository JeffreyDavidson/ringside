<?php

namespace Tests\Feature\Admin\TagTeams;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;

/**
 * @group tagteams
 * @group admins
 * @group roster
 */
class DeleteTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_delete_a_bookable_tag_team()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->bookable()->create();
        dd($tagTeam);

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertSoftDeleted('tag_teams', ['id' => $tagTeam->id]);
    }

    /** @test */
    public function an_administrator_can_delete_a_retired_tag_team()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->retired()->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertSoftDeleted('tag_teams', ['id' => $tagTeam->id]);
    }

    /** @test */
    public function an_administrator_can_delete_a_pending_employment_tag_team()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->pendingEmployment()->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertSoftDeleted('tag_teams', ['id' => $tagTeam->id]);
    }

    /** @test */
    public function an_administrator_can_delete_a_suspended_tag_team()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->suspended()->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertSoftDeleted('tag_teams', ['id' => $tagTeam->id]);
    }
}
