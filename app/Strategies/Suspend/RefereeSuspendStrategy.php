<?php

namespace App\Strategies\Suspend;

use App\Exceptions\CannotBeSuspendedException;
use App\Models\Contracts\Suspendable;
use App\Repositories\RefereeRepository;

class RefereeSuspendStrategy extends BaseSuspendStrategy implements SuspendStrategyInterface
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
     * @var \App\Repositories\RefereeRepository
     */
    private RefereeRepository $refereeRepository;

    /**
     * Create a new referee suspend strategy instance.
     */
    public function __construct()
    {
        $this->refereeRepository = new RefereeRepository;
    }

    /**
     * Undocumented function.
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

        $this->refereeRepository->suspend($this->suspendable, $suspensionDate);
        $this->suspendable->updateStatusAndSave();
    }
}
