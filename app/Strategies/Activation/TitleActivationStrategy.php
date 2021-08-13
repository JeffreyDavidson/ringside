<?php

namespace App\Strategies\Activation;

use App\Exceptions\CannotBeActivatedException;
use App\Models\Contracts\Activatable;
use App\Repositories\TitleRepository;

class TitleActivationStrategy extends BaseActivationStrategy implements ActivationStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Activatable
     */
    private Activatable $activatable;

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
     * @param  \App\Models\Contracts\Activatable $activatable
     * @return $this
     */
    public function setActivatable(Activatable $activatable)
    {
        $this->activatable = $activatable;

        return $this;
    }

    /**
     * Activate an activatable model.
     *
     * @param  string|null $activationDate
     * @return void
     */
    public function activate(string $activationDate = null)
    {
        throw_unless($this->activatable->canBeActivated(), new CannotBeActivatedException());

        $activationDate ??= now()->toDateTimeString();

        $this->titleRepository->activate($this->activatable, $activationDate);
        $this->activatable->updateStatusAndSave();
    }
}
