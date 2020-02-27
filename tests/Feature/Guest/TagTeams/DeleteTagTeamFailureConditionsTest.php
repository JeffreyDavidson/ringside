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
class DeleteTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_delete_a_tagteam()
    {
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
