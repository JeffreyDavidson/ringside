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
class EmployTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_employ_a_pending_employment_tag_team()
    {
        $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->pendingEmployment()->create();

        $response = $this->employRequest($tagTeam);

        $response->assertForbidden();
    }
}
