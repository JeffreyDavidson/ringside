<?php

namespace Tests\Feature\User\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use App\Models\TagTeam;
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
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertForbidden();
    }
}
