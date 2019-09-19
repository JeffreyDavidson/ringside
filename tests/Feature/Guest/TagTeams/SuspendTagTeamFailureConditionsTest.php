<?php

namespace Tests\Feature\Guest\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
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
        $tagTeam = factory(TagTeam::class)->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
