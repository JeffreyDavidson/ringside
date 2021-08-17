<?php

namespace Tests\Unit\Http\Controllers\TagTeams;

use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\TagTeams\RetireController;
use App\Http\Requests\TagTeams\RetireRequest;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Strategies\Retirement\TagTeamRetirementStrategy;
use Tests\TestCase;

/**
 * @group tagteams
 * @group controllers
 */
class RetireControllerTest extends TestCase
{
    /**
     * @test
     */
    public function a_retirable_tag_team_can_be_retired_with_a_given_date()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $controller = new RetireController;

        $tagTeamMock->expects()->canBeRetired()->andReturns(true);
        $tagTeamMock->expects()->isSuspended()->andReturns(false);
        $repositoryMock->expects()->release($tagTeamMock, now()->toDateTimeString())->once()->andReturns();
        $repositoryMock->expects()->retire($tagTeamMock, now()->toDateTimeString())->once()->andReturns();
        $tagTeamMock->expects()->updateStatusAndSave()->once();

        $controller->__invoke($tagTeamMock, new RetireRequest, $repositoryMock);
    }

    /**
     * @test
     */
    public function a_retirable_tag_team_that_cannot_be_retired_throws_an_exception()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $controller = new RetireController;

        $tagTeamMock->expects()->canBeRetired()->andReturns(false);
        $repositoryMock->shouldNotReceive('retire');

        $this->expectException(CannotBeRetiredException::class);

        $controller->__invoke($tagTeamMock, new RetireRequest, $repositoryMock);
    }
}
