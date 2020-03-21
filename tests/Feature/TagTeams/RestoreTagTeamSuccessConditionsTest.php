<?php

namespace Tests\Feature\Admin\TagTeams;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TagTeamFactory;
use Tests\TestCase;

/**
 * @group tagteams
 * @group admins
 * @group roster
 */
class RestoreTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_restore_a_deleted_tag_team()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertNull($tagTeam->fresh()->deleted_at);
    }
}
