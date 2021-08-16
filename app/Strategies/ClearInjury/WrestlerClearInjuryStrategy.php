<?php

namespace App\Strategies\ClearInjury;

use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Models\Contracts\Injurable;
use App\Repositories\WrestlerRepository;

class WrestlerClearInjuryStrategy extends BaseClearInjuryStrategy implements ClearInjuryStrategyInterface
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
     * @var \App\Repositories\WrestlerRepository
     */
    private WrestlerRepository $wrestlerRepository;

    /**
     * Create a new wrestler clear injury strategy instance.
     */
    public function __construct()
    {
        $this->wrestlerRepository = new WrestlerRepository;
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

        $this->wrestlerRepository->clearInjury($this->injurable, $recoveryDate);
        $this->injurable->updateStatusAndSave();

        if ($this->injurable->currentTagTeam) {
            $this->injurable->currentTagTeam->updateStatusAndSave();
        }
    }
}
