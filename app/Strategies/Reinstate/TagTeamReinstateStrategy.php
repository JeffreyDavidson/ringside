<?php

namespace App\Strategies\Reinstate;

use App\Exceptions\CannotBeReinstatedException;
use App\Models\Contracts\Reinstatable;
use App\Repositories\TagTeamRepository;

class TagTeamReinstateStrategy extends BaseReinstateStrategy implements ReinstateStrategyInterface
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
     * @var \App\Repositories\TagTeamRepository
     */
    private TagTeamRepository $tagTeamRepository;

    /**
     * Create a new tag team reinstate strategy instance.
     *
     * @param \App\Models\Contracts\Reinstatable $reinstatable
     */
    public function __construct()
    {
        $this->tagTeamRepository = new TagTeamRepository;
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

        $this->tagTeamRepository->reinstate($this->reinstatable, $reinstatementDate);
        $this->reinstatable->currentWrestlers->each->reinstate($reinstatementDate);
        $this->reinstatable->updateStatusAndSave();
    }
}
