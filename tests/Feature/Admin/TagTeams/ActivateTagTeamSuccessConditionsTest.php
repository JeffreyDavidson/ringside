<?php

namespace Tests\Feature\Admin\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group admins
 */
class ActivateTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_employ_a_pending_employment_tag_team()
    {
        $this->actAs('administrator');
        $tagteam = factory(TagTeam::class)->states('pending-employment')->create();
        dd($tagteam);

        $response = $this->put(route('tagteams.employ', $tagteam));

        $response->assertRedirect(route('tagteams.index'));
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertTrue($tagteam->is_employed);
        });
    }
}
