<?php

namespace Tests\Unit\Strategies\Activation;

use App\Exceptions\CannotBeActivatedException;
use App\Models\Title;
use App\Repositories\TitleRepository;
use App\Strategies\Activation\TitleActivationStrategy;
use Tests\TestCase;

/**
 * @group titles
 * @group strategies
 */
class TitleActivationStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_activatable_title_can_be_activated_without_a_date_passed_in()
    {
        $activationDate = null;
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleActivationStrategy($repositoryMock);

        $titleMock->expects()->canBeActivated()->once()->andReturns(true);
        $repositoryMock->expects()->activate($titleMock, $activationDate)->once()->andReturns($titleMock);

        $strategy->setActivatable($titleMock)->activate($activationDate);
    }

    /**
     * @test
     */
    public function an_activatable_title_can_be_activated_with_a_given_date()
    {
        $activationDate = now()->toDateTimeString();
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleActivationStrategy($repositoryMock);

        $titleMock->expects()->canBeActivated()->andReturns(true);
        $repositoryMock->expects()->activate($titleMock, $activationDate)->once()->andReturns();

        $strategy->setActivatable($titleMock)->activate($activationDate);
    }

    /**
     * @test
     */
    public function an_activatable_title_that_cannot_be_activated_throws_an_exception()
    {
        $activationDate = null;
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategy = new TitleActivationStrategy($repositoryMock);

        $titleMock->expects()->canBeActivated()->andReturns(false);
        $repositoryMock->shouldNotReceive('activate');

        $this->expectException(CannotBeActivatedException::class);

        $strategy->setActivatable($titleMock)->activate($activationDate);
    }
}
