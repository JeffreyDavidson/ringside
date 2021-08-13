<?php

namespace App\Strategies\Injure;

use App\Exceptions\CannotBeInjuredException;
use App\Models\Contracts\Injurable;
use App\Repositories\WrestlerRepository;

class WrestlerInjuryStrategy extends BaseInjuryStrategy implements InjuryStrategyInterface
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
     * Create a new wrestler injury strategy instance.
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
     * Injure an injurable model.
     *
     * @param  string|null $injureDate
     * @return void
     */
    public function injure(string $injureDate = null)
    {
        throw_unless($this->injurable->canBeInjured(), new CannotBeInjuredException);

        $injureDate ??= now()->toDateTimeString();

        $this->wrestlerRepository->injure($this->injurable, $injureDate);
        $this->injurable->updateStatusAndSave();

        if ($this->injurable->currentTagTeam) {
            $this->injurable->currentTagTeam->updateStatusAndSave();
        }
    }
}
