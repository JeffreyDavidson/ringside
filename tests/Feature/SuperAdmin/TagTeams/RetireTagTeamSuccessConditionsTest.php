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
class RetireTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_retire_a_bookable_tag_team()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->bookable()->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->currentRetirement->started_at);
    }

    /** @test */
    public function a_super_administrator_can_retire_a_suspended_tag_team()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->suspended()->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->currentRetirement->started_at);
    }
}
