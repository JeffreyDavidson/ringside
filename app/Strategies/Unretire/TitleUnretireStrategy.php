<?php

namespace App\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Contracts\Unretirable;
use App\Repositories\TitleRepository;

class TitleUnretireStrategy extends BaseUnretireStrategy implements UnretireStrategyInterface
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
     * @var \App\Repositories\TitleRepository
     */
    private TitleRepository $titleRepository;

    /**
     * Create a new title unretire strategy instance.
     */
    public function __construct()
    {
        $this->titleRepository = new TitleRepository;
    }

    /**
     * Undocumented function.
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

        $this->titleRepository->unretire($this->unretirable, $unretiredDate);
        $this->titleRepository->activate($this->unretirable, $unretiredDate);
        $this->unretirable->updateStatusAndSave();
    }
}
