<?php

namespace Tests\Factories;

use App\Enums\StableStatus;
use App\Models\Stable;
use Faker\Generator;

class StableFactory extends BaseFactory
{
    /** @var ActivationFactory|null */
    public $activationFactory;
    /** @var RetirementFactory|null */
    public $retirementFactory;
    /** @var WrestlerFactory|null */
    public $wrestlerFactory;
    /** @var TagTeamFactory|null */
    public $tagTeamFactory;
    public $softDeleted = false;
    protected $factoriesToClone = [
        'activationFactory',
        'retirementFactory',
        'wrestlerFactory'
    ];

    public function pendingActivation(ActivationFactory $activationFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['status'] = StableStatus::PENDING_INTRODUCTION;
        $clone->activationFactory = $activationFactory ?? ActivationFactory::new()->started(now()->addDays(2));
        $clone->retirementFactory = null;

        return $clone;
    }

    public function active(ActivationFactory $activationFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['status'] = StableStatus::ACTIVE;
        $clone->activationFactory = $activationFactory ?? ActivationFactory::new();
        $clone->retirementFactory = null;

        return $clone;
    }

    public function inactive(ActivationFactory $activationFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['status'] = StableStatus::INACTIVE;
        $clone->activationFactory = $activationFactory ?? ActivationFactory::new()->started(now()->subMonths(3))->ended(now()->subDay(1));

        return $clone;
    }

    public function retired(RetirementFactory $retirementFactory = null, ActivationFactory $activationFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['status'] = StableStatus::RETIRED;
        $clone->retirementFactory = $retirementFactory ?? RetirementFactory::new()->started(now()->subMonths(3))->ended(now()->subDay(1));
        $clone->active($activationFactory ?? $this->activationFactory);

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
        return $this->make(function ($attributes) {
            $stable = Stable::create($this->resolveAttributes($attributes));

            if ($this->activationFactory) {
                $this->activationFactory->forStable($stable)->create();
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

            if ($this->softDeleted) {
                $stable->delete();
            }

            return $stable;
        }, $attributes);
    }

    protected function defaultAttributes(Generator $faker)
    {
        return [
            'name' => $faker->name,
            'status' => StableStatus::__default,
        ];
    }
}
