<?php

namespace Tests\Unit\Strategies\Activation;

use App\Exceptions\CannotBeActivatedException;
use App\Models\Stable;
use App\Repositories\StableRepository;
use App\Strategies\Activation\StableActivationStrategy;
use Tests\TestCase;

/**
 * @group stables
 * @group strategies
 */
class StableActivationStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_activatable_stable_can_be_activated_without_a_date_passed_in()
    {
        $activationDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableActivationStrategy($repositoryMock);

        $stableMock->expects()->canBeActivated()->once()->andReturns(true);
        $repositoryMock->expects()->activate($stableMock, $activationDate)->once()->andReturns($stableMock);

        $strategy->setActivatable($stableMock)->activate($activationDate);
    }

    /**
     * @test
     */
    public function an_activatable_stable_can_be_activated_with_a_given_date()
    {
        $activationDate = now()->toDateTimeString();
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableActivationStrategy($repositoryMock);

        $stableMock->expects()->canBeActivated()->andReturns(true);
        $repositoryMock->expects()->activate($stableMock, $activationDate)->once()->andReturns();

        $strategy->setActivatable($stableMock)->activate($activationDate);
    }

    /**
     * @test
     */
    public function an_activatable_stable_that_cannot_be_activated_throws_an_exception()
    {
        $activationDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableActivationStrategy($repositoryMock);

        $stableMock->expects()->canBeActivated()->andReturns(false);
        $repositoryMock->shouldNotReceive('activate');

        $this->expectException(CannotBeActivatedException::class);

        $strategy->setActivatable($stableMock)->activate($activationDate);
    }
}
