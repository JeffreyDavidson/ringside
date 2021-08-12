<?php

namespace App\Strategies\Release;

use App\Exceptions\CannotBeReleasedException;
use App\Models\Contracts\Releasable;
use App\Repositories\ManagerRepository;

class ManagerReleaseStrategy extends BaseReleaseStrategy implements ReleaseStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Releasable
     */
    private Releasable $releasable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\ManagerRepository
     */
    private ManagerRepository $managerRepository;

    /**
     * Create a new manager releasable strategy instance.
     */
    public function __construct()
    {
        $this->managerRepository = new ManagerRepository;
    }

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Releasable $releasable
     * @return $this
     */
    public function setReleasable(Releasable $releasable)
    {
        $this->releasable = $releasable;

        return $this;
    }

    /**
     * Release a releasable model.
     *
     * @param  string|null $releaseDate
     * @return void
     */
    public function release($releaseDate = null)
    {
        throw_unless($this->releasable->canBeReleased(), new CannotBeReleasedException);

        $releaseDate ??= now()->toDateTimeString();

        if ($this->releasable->isSuspended()) {
            $this->managerRepository->reinstate($this->releasable, $releaseDate);
        }

        if ($this->releasable->isInjured()) {
            $this->managerRepository->clearInjury($this->releasable, $releaseDate);
        }

        $this->managerRepository->release($this->releasable, $releaseDate);
        $this->releasable->updateStatusAndSave();
    }
}
