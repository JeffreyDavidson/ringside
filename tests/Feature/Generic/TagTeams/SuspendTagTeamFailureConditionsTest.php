<?php

namespace Tests\Feature\Generic\TagTeams;

use App\Enums\Role;
use App\Exceptions\CannotBeSuspendedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;

/**
 * @group tagteams
 * @group generics
 * @group roster
 */
class SuspendTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

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
