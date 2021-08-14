<?php

namespace App\Strategies\Suspension;

use App\Models\Manager;
use App\Models\Referee;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Database\Eloquent\Model;

class SuspensionContextStrategy
{
    /**
     * The strategy to be used for the given model.
     *
     * @var \App\Strategies\Suspend\SuspensionStrategyInterface
     */
    private SuspensionStrategyInterface $strategy;

    /**
     * Create a new suspend context strategy instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        if ($model instanceof Manager) {
            $this->strategy = new ManagerSuspensionStrategy($model);
        } elseif ($model instanceof Referee) {
            $this->strategy = new RefereeSuspensionStrategy($model);
        } elseif ($model instanceof TagTeam) {
            $this->strategy = new TagTeamSuspensionStrategy($model);
        } elseif ($model instanceof Wrestler) {
            $this->strategy = new WrestlerSuspensionStrategy($model);
        }

        throw new \InvalidArgumentException('Could not find strategy for: '.$model::class);
    }

    /**
     * Process the suspend of the model.
     *
     * @param  string|null $suspensionDate
     * @return void
     */
    public function process(string $suspensionDate = null): void
    {
        $this->strategy->suspend($suspensionDate);
    }
}
