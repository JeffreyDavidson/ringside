<?php

namespace App\Strategies\Employment;

use App\Exceptions\CannotBeEmployedException;
use App\Models\Contracts\Employable;
use App\Repositories\ManagerRepository;

class ManagerEmploymentStrategy extends BaseEmploymentStrategy implements EmploymentStrategyInterface
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
     * @var \App\Repositories\ManagerRepository
     */
    private ManagerRepository $managerRepository;

    /**
     * Create a new manager employment strategy instance.
     */
    public function __construct()
    {
        $this->managerRepository = new ManagerRepository;
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

        $this->managerRepository->employ($this->employable, $employmentDate);
        $this->employable->updateStatusAndSave();
    }
}
