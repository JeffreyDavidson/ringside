<?php

namespace App\Strategies\Suspension;

use App\Exceptions\CannotBeSuspendedException;
use App\Models\Contracts\Suspendable;
use App\Repositories\WrestlerRepository;

class WrestlerSuspensionStrategy extends BaseSuspensionStrategy implements SuspensionStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Suspendable
     */
    private Suspendable $suspendable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\WrestlerRepository
     */
    private WrestlerRepository $wrestlerRepository;

    /**
     * Create a new wrestler suspend strategy instance.
     */
    public function __construct()
    {
        $this->wrestlerRepository = new WrestlerRepository;
    }

    /**
     * Set the suspendable model to be suspended.
     *
     * @param  \App\Models\Contracts\Suspendable $suspendable
     * @return $this
     */
    public function setSuspendable(Suspendable $suspendable)
    {
        $this->suspendable = $suspendable;

        return $this;
    }

    /**
     * Suspend a suspendable model.
     *
     * @param  string|null $suspensionDate
     * @return void
     */
    public function suspend(string $suspensionDate = null)
    {
        throw_unless($this->suspendable->canBeSuspended(), new CannotBeSuspendedException);

        $suspensionDate ??= now()->toDateTimeString();

        $this->wrestlerRepository->suspend($this->suspendable, $suspensionDate);
        $this->suspendable->updateStatusAndSave();

        if ($this->suspendable->currentTagTeam) {
            $this->suspendable->currentTagTeam->updateStatusAndSave();
        }
    }
}
