<?php

namespace Tests\Unit\Strategies\Release;

use App\Exceptions\CannotBeReleasedException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Strategies\Release\TagTeamReleaseStrategy;
use Tests\TestCase;

/**
 * @group tagteams
 * @group strategies
 */
class TagTeamReleaseStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_releasable_tag_team_can_be_released_without_a_date_passed_in()
    {
        $releaseDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamReleaseStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeReleased()->once()->andReturns(true);
        $repositoryMock->expects()->release($tagTeamMock, $releaseDate)->once()->andReturns($tagTeamMock);

        $strategy->setReleasable($tagTeamMock)->release($releaseDate);
    }

    /**
     * @test
     */
    public function a_releasable_tag_team_can_be_released_with_a_given_date()
    {
        $releaseDate = now()->toDateTimeString();
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamReleaseStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeReleased()->andReturns(true);
        $repositoryMock->expects()->release($tagTeamMock, $releaseDate)->once()->andReturns();

        $strategy->setReleasable($tagTeamMock)->release($releaseDate);
    }

    /**
     * @test
     */
    public function a_releasable_tag_team_that_cannot_be_released_throws_an_exception()
    {
        $releaseDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamReleaseStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeReleased()->andReturns(false);
        $repositoryMock->shouldNotReceive('release');

        $this->expectException(CannotBeReleasedException::class);

        $strategy->setReleasable($tagTeamMock)->release($releaseDate);
    }
}
