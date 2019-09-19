<?php

namespace Tests\Feature\User\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $this->actAs('basic-user');
        $tagTeam = factory(TagTeam::class)->states('suspended')->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertForbidden();
    }
}
