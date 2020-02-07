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
class SuspendTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_suspend_a_tagteam()
    {
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
