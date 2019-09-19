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
class DeleteTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_delete_a_tagteam()
    {
        $tagTeam = factory(TagTeam::class)->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
