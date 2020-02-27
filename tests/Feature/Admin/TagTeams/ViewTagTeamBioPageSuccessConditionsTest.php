<?php

namespace Tests\Feature\Admin\TagTeams;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TagTeamFactory;
use Tests\TestCase;

/**
 * @group tagteams
 * @group admins
 * @group roster
 */
class ViewTagTeamBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_tag_team_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->showRequest($tagTeam);

        $response->assertViewIs('tagteams.show');
        $this->assertTrue($response->data('tagTeam')->is($tagTeam));
    }
}
