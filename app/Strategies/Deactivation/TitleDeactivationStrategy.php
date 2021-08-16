<?php

namespace App\Strategies\Deactivation;

use App\Exceptions\CannotBeDeactivatedException;
use App\Models\Contracts\Deactivatable;
use App\Repositories\TitleRepository;
use App\Strategies\Deactivation\BaseDeactivationStrategy;

class TitleDeactivationStrategy extends BaseDeactivationStrategy implements DeactivationStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Deactivatable
     */
    private Deactivatable $deactivatable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\TitleRepository
     */
    private TitleRepository $titleRepository;

    /**
     * Create a new title activation strategy instance.
     *
     * @param \App\Repositories\TitleRepository $titleRepository
     */
    public function __construct()
    {
        $this->titleRepository = new TitleRepository;
    }

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Deactivatable $deactivatable
     * @return $this
     */
    public function setDeactivatable(Deactivatable $deactivatable)
    {
        $this->deactivatable = $deactivatable;

        return $this;
    }

    /**
     * Deactivate a deactivatable model.
     *
     * @param  string|null $deactivationDate
     * @return void
     */
    public function deactivate(string $deactivationDate = null)
    {
        throw_unless($this->deactivatable->canBeDeactivated(), new CannotBeDeactivatedException());

        $deactivationDate ??= now()->toDateTimeString();

        $this->titleRepository->deactivate($this->deactivatable, $deactivationDate);
        $this->deactivatable->updateStatusAndSave();
    }
}
