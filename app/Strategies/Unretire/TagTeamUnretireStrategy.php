<?php

namespace App\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Contracts\Unretirable;
use App\Repositories\TagTeamRepository;

class TagTeamUnretireStrategy extends BaseUnretireStrategy implements UnretireStrategyInterface
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
     * @var \App\Repositories\TagTeamRepository
     */
    private TagTeamRepository $tagTeamRepository;

    /**
     * Create a new tag team unretire strategy instance.
     */
    public function __construct()
    {
        $this->tagTeamRepository = new TagTeamRepository;
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

        $this->tagTeamRepository->unretire($this->unretirable, $unretiredDate);
        $this->unretirable->currentWrestlers->each->unretire($unretiredDate);
        $this->unretirable->updateStatusAndSave();

        $this->tagTeamRepository->employ($this->unretirable, $unretiredDate);
        $this->unretirable->updateStatusAndSave();
    }
}
