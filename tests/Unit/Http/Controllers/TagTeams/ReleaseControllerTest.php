<?php

namespace Tests\Unit\Http\Controllers\TagTeams;

use App\Exceptions\CannotBeReleasedException;
use App\Http\Controllers\TagTeams\ReleaseController;
use App\Http\Requests\TagTeams\ReleaseRequest;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Strategies\Release\TagTeamReleaseStrategy;
use Tests\TestCase;

/**
 * @group tagteams
 * @group controllers
 */
class ReleaseControllerTest extends TestCase
{
    /**
     * @test
     */
    public function a_releasable_tag_team_can_be_released_with_a_given_date()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $controller = new ReleaseController;

        $tagTeamMock->expects()->canBeReleased()->andReturns(true);
        $repositoryMock->expects()->release($tagTeamMock, now()->toDateTimeString())->once()->andReturns();
        $tagTeamMock->expects()->updateStatusAndSave()->once();

        $controller->__invoke($tagTeamMock, new ReleaseRequest, $repositoryMock);
    }

    /**
     * @test
     */
    public function a_releasable_tag_team_that_cannot_be_released_throws_an_exception()
    {
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $controller = new ReleaseController;

        $tagTeamMock->expects()->canBeReleased()->andReturns(false);
        $repositoryMock->shouldNotReceive('release');

        $this->expectException(CannotBeReleasedException::class);

        $controller->__invoke($tagTeamMock, new ReleaseRequest, $repositoryMock);
    }
}
