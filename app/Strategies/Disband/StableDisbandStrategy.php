<?php

namespace App\Strategies\Disband;

use App\Exceptions\CannotBeDisbandedException;
use App\Models\Contracts\Disbandable;
use App\Repositories\StableRepository;

class StableDisbandStrategy extends BaseDisbandStrategy implements DisbandStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Disbandable
     */
    private Disbandable $disbandable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\StableRepository
     */
    private StableRepository $stableRepository;

    /**
     * Create a new stable disband strategy instance.
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
     * @param  \App\Models\Contracts\Disbandable $disbandable
     * @return $this
     */
    public function setDisbandable(Disbandable $disbandable)
    {
        $this->disbandable = $disbandable;

        return $this;
    }

    /**
     * Activate an activatable model.
     *
     * @param  string|null $disbandDate
     * @return void
     */
    public function disband(string $disbandDate = null)
    {
        throw_unless($this->disbandable->canBeDisbanded(), new CannotBeDisbandedException());

        $disbandDate ??= now()->toDateTimeString();

        $this->stableRepository->disband($this->activatable, $disbandDate);
        $this->disbandable->updateStatusAndSave();
    }
}
