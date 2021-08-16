<?php

namespace Tests\Unit\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Title;
use App\Repositories\TitleRepository;
use App\Strategies\Unretire\TitleUnretireStrategy;
use Tests\TestCase;

/**
 * @group titles
 * @group strategies
 */
class TitleUnretireStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_unretirable_title_can_be_unretired_without_a_date_passed_in()
    {
        $unretireDate = null;
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleUnretireStrategy($repositoryMock);

        $titleMock->expects()->canBeUnretired()->once()->andReturns(true);
        $repositoryMock->expects()->unretire($titleMock, $unretireDate)->once()->andReturns($titleMock);

        $strategy->setUnretirable($titleMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_title_can_be_unretired_with_a_given_date()
    {
        $unretireDate = now()->toDateTimeString();
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleUnretireStrategy($repositoryMock);

        $titleMock->expects()->canBeUnretired()->andReturns(true);
        $repositoryMock->expects()->unretire($titleMock, $unretireDate)->once()->andReturns();

        $strategy->setUnretirable($titleMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_title_that_cannot_be_unretired_throws_an_exception()
    {
        $unretireDate = null;
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleUnretireStrategy($repositoryMock);

        $titleMock->expects()->canBeUnretired()->andReturns(false);
        $repositoryMock->shouldNotReceive('unretire');

        $this->expectException(CannotBeUnretiredException::class);

        $strategy->setUnretirable($titleMock)->unretire($unretireDate);
    }
}
