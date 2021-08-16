<?php

namespace App\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Contracts\Unretirable;
use App\Repositories\RefereeRepository;

class RefereeUnretireStrategy extends BaseUnretireStrategy implements UnretireStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Unretirable
     */
    private Unretirable $unretirable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\RefereeRepository
     */
    private RefereeRepository $refereeRepository;

    /**
     * Create a new referee unretire strategy instance.
     */
    public function __construct()
    {
        $this->refereeRepository = new RefereeRepository;
    }

    /**
     * Set the unretirable model to be unretired.
     *
     * @param  \App\Models\Contracts\Unretirable $unretirable
     * @return $this
     */
    public function setUnretirable(Unretirable $unretirable)
    {
        $this->unretirable = $unretirable;

        return $this;
    }

    /**
     * Unretire an unretirable model.
     *
     * @param  string|null $unretiredDate
     * @return void
     */
    public function unretire(string $unretiredDate = null)
    {
        throw_unless($this->unretirable->canBeUnretired(), new CannotBeUnretiredException);

        $unretiredDate ??= now()->toDateTimeString();

        $this->refereeRepository->unretire($this->unretirable, $unretiredDate);
        $this->refereeRepository->employ($this->unretirable, $unretiredDate);
        $this->unretirable->updateStatusAndSave();
    }
}
