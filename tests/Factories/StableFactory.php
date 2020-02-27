<?php

namespace Tests\Factories;

use App\Models\Stable;

class StableFactory extends BaseFactory
{
    /** @var EmploymentFactory|null */
    public $employmentFactory;
    /** @var SuspensionFactory|null */
    public $suspensionFactory;
    /** @var InjuryFactory|null */
    public $injuryFactory;
    /** @var RetirementFactory|null */
    public $retirementFactory;
    protected $factoriesToClone = [
        'employmentFactory',
        'suspensionFactory',
        'retirementFactory',
    ];

    public function withWrestlers(WrestlerFactory $wrestlerFactory = null)
    {
        $clone = clone $this;
        $clone->wrestlerFactory = $wrestlerFactory ?? WrestlerFactory::new()->bookable();

        return $clone;
    }

    public function withTagTeams(TagTeamFactory $tagTeamFactory = null)
    {
        $clone = clone $this;
        $clone->tagTeamFactory = $tagTeamFactory ?? TagTeamFactory::new()->bookable();

        return $clone;
    }

    public function create($attributes = [])
    {
        $stable = Stable::create($this->resolveAttributes($attributes));

        if ($this->employmentFactory) {
            $this->employmentFactory->forStable($stable)->create();
        }

        if ($this->suspensionFactory) {
            $this->suspensionFactory->forStable($stable)->create();
        }

        if ($this->retirementFactory) {
            $this->retirementFactory->forStable($stable)->create();
        }

        if ($this->wrestlerFactory) {
            $this->wrestlerFactory->forStable($stable)->create();
        }

        if ($this->tagTeamFactory) {
            $this->tagTeamFactory->forStable($stable)->create();
        }

        return $stable;
    }
}
