<?php

namespace App\Strategies\Release;

use App\Exceptions\CannotBeReleasedException;
use App\Models\Contracts\Releasable;
use App\Repositories\TagTeamRepository;

class TagTeamReleaseStrategy extends BaseReleaseStrategy implements ReleaseStrategyInterface
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
     * @var \App\Repositories\TagTeamRepository
     */
    private TagTeamRepository $tagTeamRepository;

    /**
     * Create a new tag team releasable strategy instance.
     *
     * @param \App\Models\Contracts\Releasable $releasable
     */
    public function __construct()
    {
        $this->tagTeamRepository = new TagTeamRepository;
    }

    /**
     * Set the releasable model to be released.
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
    public function release(string $releaseDate = null)
    {
        throw_unless($this->releasable->canBeReleased(), new CannotBeReleasedException());
    }
}
