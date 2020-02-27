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
class RestoreTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_restore_a_deleted_tag_team()
    {
        $this->markTestIncomplete();
        $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertForbidden();
    }
}
