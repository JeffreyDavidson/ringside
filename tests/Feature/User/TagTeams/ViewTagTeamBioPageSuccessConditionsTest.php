<?php

namespace Tests\Feature\User\TagTeams;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TagTeamFactory;
use Tests\TestCase;

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
        $signedInUser = $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->create(['user_id' => $signedInUser->id]);

        $response = $this->showRequest($tagTeam);

        $response->assertOk();
    }
}
