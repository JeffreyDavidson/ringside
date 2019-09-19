<?php

namespace Tests\Feature\User\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group users
 * @group roster
 */
class EmployTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_employ_a_pending_employment_tag_team()
    {
        $this->actAs('basic-user');
        $tagTeam = factory(TagTeam::class)->states('pending-employment')->create();

        $response = $this->employRequest($tagTeam);

        $response->assertForbidden();
    }
}
