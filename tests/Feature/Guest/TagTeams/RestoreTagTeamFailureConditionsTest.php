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
class RestoreTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_restore_a_deleted_tag_team()
    {
        $tagTeam = TagTeamFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }
}
