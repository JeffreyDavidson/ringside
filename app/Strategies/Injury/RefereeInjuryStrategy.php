<?php

namespace App\Strategies\Injury;

use App\Exceptions\CannotBeInjuredException;
use App\Models\Contracts\Injurable;
use App\Repositories\RefereeRepository;

class RefereeInjuryStrategy extends BaseInjuryStrategy implements InjuryStrategyInterface
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
     * @var \App\Repositories\RefereeRepository
     */
    private RefereeRepository $refereeRepository;

    /**
     * Create a new referee injury strategy instance.
     */
    public function __construct()
    {
        $this->refereeRepository = new RefereeRepository;
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

        $this->refereeRepository->injure($this->injurable, $injureDate);
        $this->injurable->updateStatusAndSave();
    }
}
