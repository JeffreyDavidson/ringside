<?php

namespace Tests\Feature\Generic\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use App\Exceptions\CannotBeSuspendedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->suspended()->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertForbidden();
    }
}
