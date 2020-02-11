<?php

namespace Tests\Feature\Guest\TagTeams;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group tagteams
 * @group guests
 * @group roster
 */
class ViewTagTeamsListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_view_tag_teams_page()
    {
        $response = $this->indexRequest('tag-teams');

        $response->assertRedirect(route('login'));
    }
}
