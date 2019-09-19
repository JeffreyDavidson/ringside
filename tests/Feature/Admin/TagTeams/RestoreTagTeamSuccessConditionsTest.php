<?php

namespace Tests\Feature\Admin\TagTeams;

use App\Models\TagTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group admins
 * @group roster
 */
class RestoreTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_restore_a_deleted_tag_team()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->create(['deleted_at' => today()->subDays(3)->toDateTimeString()]);

        $response = $this->restoreRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertNull($tagTeam->fresh()->deleted_at);
    }
}
