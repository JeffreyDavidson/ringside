<?php

namespace Tests\Factories;

use App\Models\Activation;
use App\Models\Stable;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ActivationFactory extends BaseFactory
{
    /** @var \Carbon\Carbon|null */
    public $startDate;
    /** @var \Carbon\Carbon|null */
    public $endDate;
    /** @var Stable[] */
    public $stables;

    /**
     * @param string|Carbon $startDate
     */
    public function started($startDate = 'now')
    {
        $clone = clone $this;
        $clone->startDate = $startDate instanceof Carbon ? $startDate : new Carbon($startDate);

        return $clone;
    }

    /**
     * @param string|Carbon $endDate
     */
    public function ended($endDate = 'now')
    {
        $clone = clone $this;
        $clone->endDate = $endDate instanceof Carbon ? $endDate : new Carbon($endDate);

        return $clone;
    }

    public function forStable(Stable $stable)
    {
        return $this->forStables([$stable]);
    }

    public function forStables($stables)
    {
        $clone = clone $this;
        $clone->stables = $stables;

        return $clone;
    }

    public function create($attributes = [])
    {
        $activators = collect()
            ->merge($this->stables)
            ->flatten(1);

        $this->startDate = $this->startDate ?? now();

        if (empty($activators)) {
            throw new \Exception('Attempted to create an activation without a employable entity');
        }

        $activations = new Collection();

        foreach ($activators as $activator) {
            $activation = new Activation();
            $activation->started_at = $this->startDate;
            $activation->ended_at = $this->endDate;
            $activation->activatable()->associate($activator);
            $activation->save();
            $activations->push($activation);
            if ($activator instanceof Stable && $activator->currentWrestlers->isNotEmpty()) {
                // $this->forWrestlers($activation->currentWrestlers)->create();
                // Stable has wrestlers involved so attach a joined at to the stable.
            }
        }

        return $activations->count() === 1 ? $activations->first() : $activations;
    }
}
