<?php

namespace Tests\Unit\Strategies\Retirement;

use App\Exceptions\CannotBeRetiredException;
use App\Models\Title;
use App\Repositories\TitleRepository;
use App\Strategies\Retirement\TitleRetirementStrategy;
use Tests\TestCase;

/**
 * @group titles
 * @group strategies
 */
class TitleRetirementStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_retirable_title_can_be_retired_without_a_date_passed_in()
    {
        $retirementDate = null;
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleRetirementStrategy($repositoryMock);

        $titleMock->expects()->canBeRetired()->once()->andReturns(true);
        $repositoryMock->expects()->retire($titleMock, $retirementDate)->once()->andReturns($titleMock);

        $strategy->setRetirable($titleMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_title_can_be_retired_with_a_given_date()
    {
        $retirementDate = now()->toDateTimeString();
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleRetirementStrategy($repositoryMock);

        $titleMock->expects()->canBeRetired()->andReturns(true);
        $repositoryMock->expects()->retire($titleMock, $retirementDate)->once()->andReturns();

        $strategy->setRetirable($titleMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_title_that_cannot_be_retired_throws_an_exception()
    {
        $retirementDate = null;
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleRetirementStrategy($repositoryMock);

        $titleMock->expects()->canBeRetired()->andReturns(false);
        $repositoryMock->shouldNotReceive('retire');

        $this->expectException(CannotBeRetiredException::class);

        $strategy->setRetirable($titleMock)->retire($retirementDate);
    }
}
