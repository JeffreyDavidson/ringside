<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use App\Models\TagTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group superadmins
 * @group roster
 */
class ViewTagTeamBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_view_a_tag_team_profile()
    {
        $this->actAs('super-administrator');
        $tagTeam = factory(TagTeam::class)->create();

        $response = $this->showRequest($tagTeam);

        $response->assertViewIs('tagteams.show');
        $this->assertTrue($response->data('tagTeam')->is($tagTeam));
    }
}
