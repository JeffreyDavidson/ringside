<?php

namespace Tests\Feature\Guest\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group guests
 * @group roster
 */
class ViewTagTeamBioPageFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_view_a_tag_team_profile()
    {
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->showRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
