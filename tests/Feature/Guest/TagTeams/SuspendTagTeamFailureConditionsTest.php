<?php

namespace Tests\Feature\Guest\TagTeams;

use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;

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
