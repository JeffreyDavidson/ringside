<?php

namespace Tests\Feature\Guest\TagTeams;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TagTeamFactory;
use Tests\TestCase;

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
