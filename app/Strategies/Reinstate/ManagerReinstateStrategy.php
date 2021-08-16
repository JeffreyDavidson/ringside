<?php

namespace App\Strategies\Reinstate;

use App\Exceptions\CannotBeReinstatedException;
use App\Models\Contracts\Reinstatable;
use App\Repositories\ManagerRepository;

class ManagerReinstateStrategy extends BaseReinstateStrategy implements ReinstateStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Reinstatable
     */
    private Reinstatable $reinstatable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\ManagerRepository
     */
    private ManagerRepository $managerRepository;

    /**
     * Create a new manager reinstate strategy instance.
     */
    public function __construct()
    {
        $this->managerRepository = new ManagerRepository;
    }

    /**
     * Set the reinstatable model to be reinstated.
     *
     * @param  \App\Models\Contracts\Reinstatable $reinstatable
     * @return $this
     */
    public function setReinstatable(Reinstatable $reinstatable)
    {
        $this->reinstatable = $reinstatable;

        return $this;
    }

    /**
     * Reinstate a reinstatable model.
     *
     * @param  string|null $reinstatementDate
     * @return void
     */
    public function reinstate(string $reinstatementDate = null)
    {
        throw_unless($this->reinstatable->canBeReinstated(), new CannotBeReinstatedException);

        $reinstatementDate ??= now()->toDateTimeString();

        $this->managerRepository->reinstate($this->reinstatable, $reinstatementDate);
        $this->reinstatable->updateStatusAndSave();
    }
}
