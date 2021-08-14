<?php

namespace App\Strategies\Injury;

use App\Exceptions\CannotBeInjuredException;
use App\Models\Contracts\Injurable;
use App\Repositories\ManagerRepository;

class ManagerInjuryStrategy extends BaseInjuryStrategy implements InjuryStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Injurable
     */
    private Injurable $injurable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\ManagerRepository
     */
    private ManagerRepository $managerRepository;

    /**
     * Create a new manager injury strategy instance.
     */
    public function __construct()
    {
        $this->managerRepository = new ManagerRepository;
    }

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Injurable $injurable
     * @return $this
     */
    public function setInjurable(Injurable $injurable)
    {
        $this->injurable = $injurable;

        return $this;
    }

    /**
     * Injure an injurable model.
     *
     * @param  string|null $injureDate
     * @return void
     */
    public function injure(string $injureDate = null)
    {
        throw_unless($this->injurable->canBeInjured(), new CannotBeInjuredException);

        $injureDate ??= now()->toDateTimeString();

        $this->managerRepository->injure($this->injurable, $injureDate);
        $this->injurable->updateStatusAndSave();
    }
}
