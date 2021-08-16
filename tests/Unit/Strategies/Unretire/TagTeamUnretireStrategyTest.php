<?php

namespace Tests\Unit\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Strategies\Unretire\TagTeamUnretireStrategy;
use Tests\TestCase;

/**
 * @group tagteams
 * @group strategies
 */
class TagTeamUnretireStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_unretirable_tag_team_can_be_unretired_without_a_date_passed_in()
    {
        $unretireDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamUnretireStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeUnretired()->once()->andReturns(true);
        $repositoryMock->expects()->unretire($tagTeamMock, $unretireDate)->once()->andReturns($tagTeamMock);

        $strategy->setUnretirable($tagTeamMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_tag_team_can_be_unretired_with_a_given_date()
    {
        $unretireDate = now()->toDateTimeString();
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamUnretireStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeUnretired()->andReturns(true);
        $repositoryMock->expects()->unretire($tagTeamMock, $unretireDate)->once()->andReturns();

        $strategy->setUnretirable($tagTeamMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_tag_team_that_cannot_be_unretired_throws_an_exception()
    {
        $unretireDate = null;
        $tagTeamMock = $this->mock(TagTeam::class);
        $repositoryMock = $this->mock(TagTeamRepository::class);
        $strategy = new TagTeamUnretireStrategy($repositoryMock);

        $tagTeamMock->expects()->canBeUnretired()->andReturns(false);
        $repositoryMock->shouldNotReceive('unretire');

        $this->expectException(CannotBeUnretiredException::class);

        $strategy->setUnretirable($tagTeamMock)->unretire($unretireDate);
    }
}
