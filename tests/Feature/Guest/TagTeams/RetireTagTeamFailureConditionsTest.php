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
class RetireTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_retire_a_tag_team()
    {
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
