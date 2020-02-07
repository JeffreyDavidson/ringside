<?php

namespace Tests\Feature\Guest\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group guests
 * @group roster
 */
class ReinstateTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_reinstate_a_suspended_tag_team()
    {
        $tagTeam = TagTeamFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
