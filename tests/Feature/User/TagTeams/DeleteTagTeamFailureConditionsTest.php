<?php

namespace Tests\Feature\User\TagTeams;

use App\Models\TagTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group users
 * @group roster
 */
class DeleteTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_delete_a_tagteam()
    {
        $this->actAs('basic-user');
        $tagTeam = factory(TagTeam::class)->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertForbidden();
    }
}
