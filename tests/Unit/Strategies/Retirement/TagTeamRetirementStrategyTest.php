<?php

namespace Tests\Unit\Strategies\Retirement;

use App\Exceptions\CannotBeRetiredException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Strategies\Retirement\TagTeamRetirementStrategy;
use Tests\TestCase;

/**
 * @group tagteams
 * @group strategies
 */
class TagTeamRetirementStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_retirable_tag_team_can_be_retired_without_a_date_passed_in()
    {
        $retirementDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamRetirementStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeRetired()->once()->andReturns(true);
        $repositoryMock->expects()->retire($tagTeamMock, $retirementDate)->once()->andReturns($tagTeamMock);

        $strategy->setRetirable($tagTeamMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_tag_team_can_be_retired_with_a_given_date()
    {
        $retirementDate = now()->toDateTimeString();
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamRetirementStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeRetired()->andReturns(true);
        $repositoryMock->expects()->retire($tagTeamMock, $retirementDate)->once()->andReturns();

        $strategy->setRetirable($tagTeamMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_tag_team_that_cannot_be_retired_throws_an_exception()
    {
        $retirementDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamRetirementStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeRetired()->andReturns(false);
        $repositoryMock->shouldNotReceive('retire');

        $this->expectException(CannotBeRetiredException::class);

        $strategy->setRetirable($tagTeamMock)->retire($retirementDate);
    }
}
