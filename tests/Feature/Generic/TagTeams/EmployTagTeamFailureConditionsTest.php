<?php

namespace Tests\Feature\Generic\TagTeams;

use App\Exceptions\CannotBeEmployedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;

/**
 * @group tagteams
 * @group generics
 * @group roster
 */
class EmployTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_tag_team_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->bookable()->create();

        $response = $this->employRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_tag_team_without_wrestlers_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->pendingEmployment()->withoutWrestlers()->create();

        $response = $this->employRequest($tagTeam);

        $response->assertForbidden();
    }
}
