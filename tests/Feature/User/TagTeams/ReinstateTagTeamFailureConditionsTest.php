<?php

namespace Tests\Feature\User\TagTeams;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;

/**
 * @group tagteams
 * @group users
 * @group roster
 */
class ReinstateTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_reinstate_a_suspended_tag_team()
    {
        $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertForbidden();
    }
}
