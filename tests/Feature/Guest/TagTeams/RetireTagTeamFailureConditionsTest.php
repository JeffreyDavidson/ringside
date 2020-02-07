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
