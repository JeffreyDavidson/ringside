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
class ReinstateTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_reinstate_a_suspended_tag_team()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->suspensions()->latest()->first()->ended_at);
    }
}
