<?php

namespace App\Strategies\Retirement;

use App\Exceptions\CannotBeRetiredException;
use App\Models\Contracts\Retirable;
use App\Repositories\WrestlerRepository;

class WrestlerRetirementStrategy extends BaseRetirementStrategy implements RetirementStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Retirable
     */
    private Retirable $retirable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\WrestlerRepository
     */
    private WrestlerRepository $wrestlerRepository;

    /**
     * Create a new wrestler retirement strategy instance.
     */
    public function __construct()
    {
        $this->wrestlerRepository = new WrestlerRepository;
    }

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Retirable $retirable
     * @return $this
     */
    public function setRetirable(Retirable $retirable)
    {
        $this->retirable = $retirable;

        return $this;
    }

    /**
     * Retire a retirable model.
     *
     * @param  string|null $retirementDate
     * @return void
     */
    public function retire(string $retirementDate = null)
    {
        throw_unless($this->retirable->canBeRetired(), new CannotBeRetiredException);

        $retirementDate ??= now()->toDateTimeString();

        if ($this->retirable->isSuspended()) {
            $this->wrestlerRepository->reinstate($this->retirable, $retirementDate);
        }

        if ($this->retirable->isInjured()) {
            $this->wrestlerRepository->clearInjury($this->retirable, $retirementDate);
        }

        $this->wrestlerRepository->release($this->retirable, $retirementDate);
        $this->wrestlerRepository->retire($this->retirable, $retirementDate);

        $this->retirable->updateStatusAndSave();

        if ($this->retirable->currentTagTeam) {
            $this->retirable->currentTagTeam->updateStatusAndSave();
        }
    }
}
