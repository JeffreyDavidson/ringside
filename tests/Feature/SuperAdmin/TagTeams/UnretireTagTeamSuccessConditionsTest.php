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
class UnretireTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_unretire_a_retired_tag_team()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->retired()->create();

        $response = $this->unretireRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->retirements()->latest()->first()->ended_at);
    }
}
