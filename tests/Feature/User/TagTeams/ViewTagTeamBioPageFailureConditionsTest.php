<?php

namespace Tests\Feature\User\TagTeams;

use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;
use UserFactory;

/**
 * @group tagteams
 * @group users
 * @group roster
 */
class ViewTagTeamBioPageFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_another_users_tag_team_profile()
    {
        $this->actAs('basic-user');
        $otherUser = UserFactory::new()->create();
        $tagTeam = TagTeamFactory::new()->create(['user_id' => $otherUser->id]);

        $response = $this->showRequest($tagTeam);

        $response->assertForbidden();
    }
}
