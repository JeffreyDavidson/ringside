<?php

namespace Tests\Feature\User\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group users
 * @group roster
 */
class SuspendTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_suspend_a_tag_team()
    {
        $this->actAs('basic-user');
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertForbidden();
    }
}
