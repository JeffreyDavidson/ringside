<?php

namespace App\Strategies\Activation;

use App\Exceptions\CannotBeActivatedException;
use App\Models\Contracts\Activatable;
use App\Repositories\StableRepository;

class StableActivationStrategy extends BaseActivationStrategy implements ActivationStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Activatable
     */
    private Activatable $activatable;

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
     * @param  \App\Models\Contracts\Activatable $activatable
     * @return $this
     */
    public function setActivatable(Activatable $activatable)
    {
        $this->activatable = $activatable;

        return $this;
    }

    /**
     * Activate an activatable model.
     *
     * @param  string|null $activationDate
     * @return void
     */
    public function activate(string $activationDate = null)
    {
        throw_unless($this->activatable->canBeActivated(), new CannotBeActivatedException());

        $activationDate ??= now()->toDateTimeString();

        $this->stableRepository->activate($this->activatable, $activationDate);
        $this->activatable->updateStatusAndSave();
    }
}
