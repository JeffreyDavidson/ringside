<?php

namespace Tests\Factories;

use App\Enums\StableStatus;
use App\Models\Stable;
use Faker\Generator;

class StableFactory extends BaseFactory
{
    /** @var RetirementFactory|null */
    public $retirementFactory;
    protected $factoriesToClone = [
        'retirementFactory',
    ];

    public function pendingIntroduction()
    {
        $clone = clone $this;
        $clone->attributes['status'] = StableStatus::PENDING_INTRODUCTION;
        $clone->retirementFactory = null;

        return $clone;
    }

    public function active()
    {
        $clone = clone $this;
        $clone->attributes['status'] = StableStatus::ACTIVE;
        $clone->retirementFactory = null;

        return $clone;
    }

    public function retired(RetirementFactory $retirementFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['status'] = StableStatus::RETIRED;
        $clone->retirementFactory = $retirementFactory ?? RetirementFactory::new();

        return $clone;
    }

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

    protected function defaultAttributes(Generator $faker)
    {
        return [
            'name' => $faker->name,
            'status' => StableStatus::__default,
        ];
    }
}
