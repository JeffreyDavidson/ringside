<?php

namespace App\Strategies\ClearInjury;

use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Models\Contracts\Injurable;
use App\Repositories\RefereeRepository;

class RefereeClearInjuryStrategy extends BaseClearInjuryStrategy implements ClearInjuryStrategyInterface
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
     * Create a new referee clear injury strategy instance.
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
     * Clear an injury of an injurable model.
     *
     * @param  string|null $recoveryDate
     * @return void
     */
    public function clearInjury(string $recoveryDate = null)
    {
        throw_unless($this->injurable->canBeClearedFromInjury(), new CannotBeClearedFromInjuryException);

        $recoveryDate ??= now()->toDateTimeString();

        $this->refereeRepository->clearInjury($this->injurable, $recoveryDate);
        $this->injurable->updateStatusAndSave();
    }
}
