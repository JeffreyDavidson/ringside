<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use TagTeamFactory;
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
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->showRequest($tagTeam);

        $response->assertViewIs('tagteams.show');
        $this->assertTrue($response->data('tagTeam')->is($tagTeam));
    }
}
