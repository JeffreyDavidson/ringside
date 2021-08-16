<?php

namespace App\Strategies\Employment;

use App\Models\Contracts\Employable;

interface EmploymentStrategyInterface
{
    /**
     * Employ an employable model.
     *
     * @param  string|null $employmentDate
     * @return void
     */
    public function employ(string $employmentDate = null);

    /**
     * Set the employable model to be employed.
     *
     * @param  \App\Models\Contracts\Employable $employable
     * @return void
     */
    public function setEmployable(Employable $employable);
}
