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
class SuspendTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_suspend_a_tag_team_that_is_pending_employment()
    {
        $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->pendingEmployment()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_suspend_a_bookable_tag_team()
    {
        $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->bookable()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_suspend_a_retired_tag_team()
    {
        $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->pendingEmployment()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_suspend_a_suspended_tag_team()
    {
        $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->suspended()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_suspend_a_tagteam()
    {
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_already_suspended_tag_team_cannot_be_suspended()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeSuspendedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->suspended()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertForbidden();
    }
}
