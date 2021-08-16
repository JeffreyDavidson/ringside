<?php

namespace Tests\Unit\Strategies\Reinstate;

use App\Exceptions\CannotBeReinstatedException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Strategies\Reinstate\TagTeamReinstateStrategy;
use Tests\TestCase;

/**
 * @group tagteams
 * @group strategies
 */
class TagTeamReinstateStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_reinstatable_tag_team_can_be_reinstated_without_a_date_passed_in()
    {
        $reinstatementDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamReinstateStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeReinstated()->once()->andReturns(true);
        $repositoryMock->expects()->reinstate($tagTeamMock, $reinstatementDate)->once()->andReturns($tagTeamMock);

        $strategy->setReinstatable($tagTeamMock)->reinstate($reinstatementDate);
    }

    /**
     * @test
     */
    public function a_reinstatable_tag_team_can_be_reinstated_with_a_given_date()
    {
        $reinstatementDate = now()->toDateTimeString();
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamReinstateStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeReinstated()->andReturns(true);
        $repositoryMock->expects()->release($tagTeamMock, $reinstatementDate)->once()->andReturns();

        $strategy->setReinstatable($tagTeamMock)->reinstate($reinstatementDate);
    }

    /**
     * @test
     */
    public function a_reinstatable_tag_team_that_cannot_be_reinstated_throws_an_exception()
    {
        $reinstatementDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamReinstateStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeReinstated()->andReturns(false);
        $repositoryMock->shouldNotReceive('reinstate');

        $this->expectException(CannotBeReinstatedException::class);

        $strategy->setReinstatable($tagTeamMock)->reinstate($reinstatementDate);
    }
}
