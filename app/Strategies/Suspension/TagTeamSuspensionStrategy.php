<?php

namespace App\Strategies\Suspension;

use App\Exceptions\CannotBeSuspendedException;
use App\Models\Contracts\Suspendable;
use App\Repositories\TagTeamRepository;

class TagTeamSuspensionStrategy extends BaseSuspensionStrategy implements SuspensionStrategyInterface
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
     * @var \App\Repositories\TagTeamRepository
     */
    private TagTeamRepository $tagTeamRepository;

    /**
     * Create a new tag team suspend strategy instance.
     */
    public function __construct()
    {
        $this->tagTeamRepository = new TagTeamRepository;
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

        $this->tagTeamRepository->suspend($this->suspendable, $suspensionDate);

        $this->suspendable->currentWrestlers->each->suspend($suspensionDate);
        $this->suspendable->updateStatusAndSave();
    }
}
