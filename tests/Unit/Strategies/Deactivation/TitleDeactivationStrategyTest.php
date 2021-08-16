<?php

namespace Tests\Unit\Strategies\Deactivation;

use App\Exceptions\CannotBeDeactivatedException;
use App\Models\Title;
use App\Repositories\TitleRepository;
use App\Strategies\Deactivation\TitleDeactivationStrategy;
use Tests\TestCase;

/**
 * @group titles
 * @group strategies
 */
class TitleDeactivationStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_deactivatable_title_can_be_deactivated_without_a_date_passed_in()
    {
        $deactivationDate = null;
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleDeactivationStrategy($repositoryMock);

        $titleMock->expects()->canBeDeactivated()->once()->andReturns(true);
        $repositoryMock->expects()->deactivate($titleMock, $deactivationDate)->once()->andReturns($titleMock);

        $strategy->setDeactivatable($titleMock)->deactivate($deactivationDate);
    }

    /**
     * @test
     */
    public function a_deactivatable_title_can_be_deactivated_with_a_given_date()
    {
        $deactivationDate = now()->toDateTimeString();
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleDeactivationStrategy($repositoryMock);

        $titleMock->expects()->canBeDeactivated()->andReturns(true);
        $repositoryMock->expects()->deactivate($titleMock, $deactivationDate)->once()->andReturns();

        $strategy->setDeactivatable($titleMock)->deactivate($deactivationDate);
    }

    /**
     * @test
     */
    public function a_deactivatable_title_that_cannot_be_deactivated_throws_an_exception()
    {
        $deactivationDate = null;
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleDeactivationStrategy($repositoryMock);

        $titleMock->expects()->canBeDeactivated()->andReturns(false);
        $repositoryMock->shouldNotReceive('deactivate');

        $this->expectException(CannotBeDeactivatedException::class);

        $strategy->setDeactivatable($titleMock)->deactivate($deactivationDate);
    }
}
