<?php

namespace App\Strategies\Reinstate;

use App\Exceptions\CannotBeReinstatedException;
use App\Models\Contracts\Reinstatable;
use App\Repositories\RefereeRepository;

class RefereeReinstateStrategy extends BaseReinstateStrategy implements ReinstateStrategyInterface
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
     * @var \App\Repositories\RefereeRepository
     */
    private RefereeRepository $refereeRepository;

    /**
     * Create a new referee reinstate strategy instance.
     */
    public function __construct()
    {
        $this->refereeRepository = new RefereeRepository;
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

        $this->refereeRepository->reinstate($this->reinstatable, $reinstatementDate);
        $this->reinstatable->updateStatusAndSave();
    }
}
