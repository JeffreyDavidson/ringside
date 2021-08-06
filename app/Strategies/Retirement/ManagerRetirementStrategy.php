<?php

namespace App\Strategies\Retirement;

use App\Exceptions\CannotBeRetiredException;
use App\Models\Contracts\Retirable;
use App\Strategies\ClearInjury\ManagerClearInjuryStrategy;
use App\Strategies\Reinstate\ManagerReinstateStrategy;
use Carbon\Carbon;

class ManagerRetirementStrategy extends BaseRetirementStrategy implements RetirementStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Retirable
     */
    private Retirable $retirable;

    /**
     * Create a new manager retirement strategy instance.
     *
     * @param \App\Models\Contracts\Retirable $retirable
     */
    public function __construct(Retirable $retirable)
    {
        $this->retirable = $retirable;
    }

    /**
     * Retire a retirable model.
     *
     * @param  \Carbon\Carbon|null $retiredAt
     * @return void
     */
    public function retire(Carbon $retiredAt = null)
    {
        throw_unless($this->retirable->canBeRetired(), new CannotBeRetiredException);

        if ($this->retirable->isSuspended()) {
            (new ManagerReinstateStrategy($this->retirable))->reinstate();
        }

        if ($this->retirable->isInjured()) {
            (new ManagerClearInjuryStrategy($this->retirable))->clearInjury();
        }

        $retiredDate = Carbon::parse($retiredAt)->toDateTimeString() ?: now()->toDateTimeString();

        $this->retirable->currentEmployment()->update(['ended_at' => $retiredDate]);
        $this->retirable->retirements()->create(['started_at' => $retiredDate]);
        $this->retirable->updateStatusAndSave();
    }
}
