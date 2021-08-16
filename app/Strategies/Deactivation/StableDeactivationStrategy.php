<?php

namespace App\Strategies\Deactivation;

use App\Exceptions\CannotBeDeactivatedException;
use App\Models\Contracts\Deactivatable;
use App\Repositories\StableRepository;
use App\Strategies\Deactivation\BaseDeactivationStrategy;

class StableDeactivationStrategy extends BaseDeactivationStrategy implements DeactivationStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Activatable
     */
    private Deactivatable $deactivatable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\StableRepository
     */
    private StableRepository $stableRepository;

    /**
     * Create a new stable activation strategy instance.
     *
     * @param \App\Repositories\StableRepository $stableRepository
     */
    public function __construct()
    {
        $this->stableRepository = new StableRepository;
    }

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Deactivatable $deactivatable
     * @return $this
     */
    public function setDeactivatable(Deactivatable $deactivatable)
    {
        $this->deactivatable = $deactivatable;

        return $this;
    }

    /**
     * Deactivate a deactivatable model.
     *
     * @param  string|null $deactivationDate
     * @return void
     */
    public function deactivate(string $deactivationDate = null)
    {
        throw_unless($this->deactivatable->canBeDeactivated(), new CannotBeDeactivatedException());

        $deactivationDate ??= now()->toDateTimeString();

        $this->stableRepository->deactivate($this->deactivatable, $deactivationDate);
        $this->deactivatable->updateStatusAndSave();
    }
}
