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
class RetireTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_retire_a_bookable_tag_team()
    {
        $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->bookable()->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertForbidden();
    }
}
