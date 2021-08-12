<?php

namespace App\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Contracts\Unretirable;
use App\Repositories\ManagerRepository;

class ManagerUnretireStrategy extends BaseUnretireStrategy implements UnretireStrategyInterface
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
     * @var \App\Repositories\ManagerRepository
     */
    private ManagerRepository $managerRepository;

    /**
     * Create a new manager unretire strategy instance.
     */
    public function __construct()
    {
        $this->managerRepository = new ManagerRepository;
    }

    /**
     * Undocumented function.
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

        $this->managerRepository->unretire($this->unretirable, $unretiredDate);
        $this->managerRepository->employ($this->unretirable, $unretiredDate);
        $this->unretirable->updateStatusAndSave();
    }
}
