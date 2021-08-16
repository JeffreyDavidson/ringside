<?php

namespace App\Strategies\Employment;

use App\Exceptions\CannotBeEmployedException;
use App\Models\Contracts\Employable;
use App\Repositories\RefereeRepository;

class RefereeEmploymentStrategy extends BaseEmploymentStrategy implements EmploymentStrategyInterface
{
    /**
     * The interface implementation.
     *
     * @var \App\Models\Contracts\Employable
     */
    private Employable $employable;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\RefereeRepository
     */
    private RefereeRepository $refereeRepository;

    /**
     * Create a new referee employment strategy instance.
     */
    public function __construct()
    {
        $this->refereeRepository = new RefereeRepository;
    }

    /**
     * Set the employable model to be employed.
     *
     * @param  \App\Models\Contracts\Employable $employable
     * @return $this
     */
    public function setEmployable(Employable $employable)
    {
        $this->employable = $employable;

        return $this;
    }

    /**
     * Employ an employable model.
     *
     * @param  string|null $employmentDate
     * @return void
     */
    public function employ(string $employmentDate = null)
    {
        throw_unless($this->employable->canBeEmployed(), new CannotBeEmployedException);

        $employmentDate ??= now()->toDateTimeString();

        $this->refereeRepository->employ($this->employable, $employmentDate);
        $this->employable->updateStatusAndSave();
    }
}
