<?php

namespace Tests\Feature\Generic\TagTeams;

use App\Models\TagTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group generics
 * @group roster
 */
class ViewTagTeamBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_tag_teams_data_can_be_seen_on_their_profile()
    {
        $signedInUser = $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->create([
            'name' => 'Tag Team 1',
            'signature_move' => 'The Finisher',
        ]);

        $response = $this->showRequest($tagTeam);

        $response->assertSee('Tag Team 1');
        $response->assertSee($tagTeam->combinedWeight);
        $response->assertSee('The Finisher');
    }
}
