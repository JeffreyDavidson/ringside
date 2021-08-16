<?php

namespace Tests\Unit\Strategies\Suspension;

use App\Exceptions\CannotBeSuspendedException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Strategies\Suspension\TagTeamSuspensionStrategy;
use Tests\TestCase;

/**
 * @group tagteams
 * @group strategies
 */
class TagTeamSuspensionStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_suspendable_tag_team_can_be_suspended_without_a_date_passed_in()
    {
        $suspensionDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamSuspensionStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeSuspended()->once()->andReturns(true);
        $repositoryMock->expects()->suspend($tagTeamMock, $suspensionDate)->once()->andReturns($tagTeamMock);

        $strategy->setSuspendable($tagTeamMock)->suspend($suspensionDate);
    }

    /**
     * @test
     */
    public function a_suspendable_tag_team_can_be_suspended_with_a_given_date()
    {
        $suspensionDate = now()->toDateTimeString();
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamSuspensionStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeSuspended()->andReturns(true);
        $repositoryMock->expects()->suspend($tagTeamMock, $suspensionDate)->once()->andReturns();

        $strategy->setSuspendable($tagTeamMock)->suspend($suspensionDate);
    }

    /**
     * @test
     */
    public function a_suspendable_tag_team_that_cannot_be_suspended_throws_an_exception()
    {
        $suspensionDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamSuspensionStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeSuspended()->andReturns(false);
        $repositoryMock->shouldNotReceive('suspend');

        $this->expectException(CannotBeSuspendedException::class);

        $strategy->setSuspendable($tagTeamMock)->suspend($suspensionDate);
    }
}
