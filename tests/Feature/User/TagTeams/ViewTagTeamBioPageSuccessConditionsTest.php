<?php

namespace Tests\Feature\User\TagTeams;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;

/**
 * @group tagteams
 * @group users
 * @group roster
 */
class ViewTagTeamBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_can_view_their_tag_team_profile()
    {
        $signedInUser = $this->actAs('basic-user');
        $tagTeam = TagTeamFactory::new()->create(['user_id' => $signedInUser->id]);

        $response = $this->showRequest($tagTeam);

        $response->assertOk();
    }
}
