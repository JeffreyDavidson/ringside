<?php

namespace Tests\Feature\Guest\TagTeams;

use App\Models\TagTeam;
use Tests\TestCase;
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
        $tagTeam = factory(TagTeam::class)->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
