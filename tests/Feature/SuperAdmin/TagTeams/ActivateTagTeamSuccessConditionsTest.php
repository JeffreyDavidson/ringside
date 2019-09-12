<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group superadmins
 */
class ActivateTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_activate_a_pending_employment_tag_team()
    {
        $this->actAs('super-administrator');
        $tagteam = factory(TagTeam::class)->states('pending-employment')->create();

        $response = $this->put(route('tagteams.activate', $tagteam));

        $response->assertRedirect(route('tagteams.index'));
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertTrue($tagteam->is_bookable);
        });
    }
}
