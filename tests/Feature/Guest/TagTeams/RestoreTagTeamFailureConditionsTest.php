<?php

namespace Tests\Feature\Guest\TagTeams;

use App\Models\TagTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group guests
 * @group roster
 */
class RestoreTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_restore_a_deleted_tag_team()
    {
        $this->actAs('basic-user');
        $tagTeam = factory(TagTeam::class)->create(['deleted_at' => today()->subDays(3)->toDateTimeString()]);

        $response = $this->restoreRequest($tagTeam);

        $response->assertForbidden();
    }
}
