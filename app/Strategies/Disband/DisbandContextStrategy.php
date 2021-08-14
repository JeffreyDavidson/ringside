<?php

namespace App\Strategies\Disband;

use App\Models\Stable;
use Illuminate\Database\Eloquent\Model;

class DisbandContextStrategy
{
    /**
     * The strategy to be used for the given model.
     *
     * @var \App\Strategies\Activation\DisbandStrategyInterface
     */
    private DisbandStrategyInterface $strategy;

    /**
     * Create a new activation context strategy instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        if ($model instanceof Stable) {
            $this->strategy = new StableDisbandStrategy($model);
        }

        throw new \InvalidArgumentException('Could not find strategy for: '.$model::class);
    }

    /**
     * Process the activation of the model.
     *
     * @param  string|null $disbandDate
     * @return void
     */
    public function process($disbandDate = null)
    {
        $this->strategy->activate($disbandDate);
    }
}
