<?php

namespace App\Services;

use App\Models\Title;
use App\Repositories\TitleRepository;
use App\Strategies\Activation\TitleActivationStrategy;
use App\Strategies\Deactivation\TitleDeactivationStrategy;
use App\Strategies\Retirement\TitleRetirementStrategy;
use App\Strategies\Unretire\TitleUnretireStrategy;

class TitleService
{
    /**
     * The repository implementation.
     *
     * @var \App\Repositories\TitleRepository
     */
    protected $titleRepository;

    /**
     * Create a new title service instance.
     *
     * @param \App\Repositories\TitleRepository $titleRepository
     */
    public function __construct(TitleRepository $titleRepository)
    {
        $this->titleRepository = $titleRepository;
    }

    /**
     * Create a title with given data.
     *
     * @param  array $data
     * @return \App\Models\Title
     */
    public function create(array $data)
    {
        $title = $this->titleRepository->create($data);

        if (isset($data['activated_at'])) {
            app()->make(TitleActivationStrategy::class)->setActivatable($title)->activate($data['activated_at']);
        }

        return $title;
    }

    /**
     * Update a given title with given data.
     *
     * @param  \App\Models\Title $title
     * @param  array $data
     * @return \App\Models\Title $title
     */
    public function update(Title $title, array $data)
    {
        $this->titleRepository->update($title, $data);

        if (isset($data['activated_at'])) {
            $this->activateOrUpdateActivation($title, $data['activated_at']);
        }

        return $title;
    }

    /**
     * Activate a given manager or update the given title's activation date.
     *
     * @param  \App\Models\Title $title
     * @param  string $activationDate
     * @return \App\Models\Stable
     */
    public function activateOrUpdateActivation(Title $title, string $activationDate)
    {
        if ($title->isNotInActivation()) {
            return app()->make(TitleActivationStrategy::class)->setActivatable($title)->activate($activationDate);
        }

        if ($title->hasFutureActivation() && ! $title->activatedOn($activationDate)) {
            return $this->titleRepository->updateActivation($title, $activationDate);
        }
    }

    /**
     * Delete a given title.
     *
     * @param  \App\Models\Title $title
     * @return void
     */
    public function delete(Title $title)
    {
        $this->titleRepository->delete($title);
    }

    /**
     * Restore a given title.
     *
     * @param  \App\Models\Title $title
     * @return void
     */
    public function restore(Title $title)
    {
        $this->titleRepository->restore($title);
    }

    /**
     * Activate a given title.
     *
     * @param  \App\Models\Title $title
     * @return void
     */
    public function activate(Title $title)
    {
        app()->make(TitleActivationStrategy::class)->setActivatable($title)->activate();
    }

    /**
     * Deactivate a given title.
     *
     * @param  \App\Models\Title $title
     * @return void
     */
    public function deactivate(Title $title)
    {
        app()->make(TitleDeactivationStrategy::class)->setDeactivatable($title)->deactivate();
    }

    /**
     * Retire a given title.
     *
     * @param  \App\Models\Title $title
     * @return void
     */
    public function retire(Title $title)
    {
        app()->make(TitleRetirementStrategy::class)->setRetirable($title)->retire();
    }

    /**
     * Unretire a given title.
     *
     * @param  \App\Models\Title $title
     * @return void
     */
    public function unretire(Title $title)
    {
        app()->make(TitleUnretireStrategy::class)->setUnretirable($title)->unretire();
    }
}
