<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;

/**
 * @group tagteams
 * @group superadmins
 * @group roster
 */
class SuspendTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_suspend_a_bookable_tag_team()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->bookable()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->currentSuspension->started_at);
    }
}
