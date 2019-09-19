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
class EmployTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_employ_an_pending_employment_tag_team()
    {
        $tagTeam = factory(TagTeam::class)->states('pending-employment')->create();

        $response = $this->employRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
