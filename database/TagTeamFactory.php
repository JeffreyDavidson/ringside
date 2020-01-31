<?php

use App\Models\TagTeam;
use App\Enums\TagTeamStatus;

class TagTeamFactory extends BaseFactory
{
    public $attributes = [];
    /** @var EmploymentFactory|null */
    public $employmentFactory;
    /** @var SuspensionFactory|null */
    public $suspensionFactory;
    /** @var WrestlerFactory|null */
    public $wrestlerFactory;
    /** @var InjuryFactory|null */
    public $injuryFactory;
    /** @var RetirementFactory|null */
    public $retirementFactory;
    protected $propertiesToClone = [
        'employmentFactory',
        'suspensionFactory',
        'wrestlerFactory',
        'injuryFactory',
        'retirementFactory'
    ];

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public static function new(array $attributes = [])
    {
        return new static($attributes);
    }

    public function pendingEmployment(): self
    {
        $clone = clone $this;
        $clone->status = TagTeamStatus::PENDING_EMPLOYMENT;
        // We set these to null since we can't be pending employment if they're set
        $clone->employmentFactory = null;
        $clone->suspensionFactory = null;
        $clone->injuryFactory = null;
        $clone->retirementFactory = null;

        return $clone;
    }

    public function employed(EmploymentFactory $employmentFactory = null)
    {
        $clone = clone $this;
        $clone->employmentFactory = $employmentFactory ?? EmploymentFactory::new();

        return $clone;
    }

    public function bookable(EmploymentFactory $employmentFactory = null)
    {
        $clone = clone $this;
        $clone->status = TagTeamStatus::BOOKABLE;
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);
        $clone = $clone->withWrestlers($wrestlerFactory ?? $this->wrestlerFactory);
        // We set these to null since a TagTeam cannot be bookable if any of these exist
        $clone->suspensionFactory = null;
        $clone->injuryFactory = null;
        $clone->retirementFactory = null;

        return $clone;
    }

    public function suspended(SuspensionFactory $suspensionFactory = null, EmploymentFactory $employmentFactory = null): self
    {
        $clone = clone $this;
        $clone->status = TagTeamStatus::SUSPENDED;
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);
        $clone = $clone->withWrestlers($wrestlerFactory ?? $this->wrestlerFactory);

        $clone->suspensionFactory = $suspensionFactory ?? $this->suspensionFactory ?? SuspensionFactory::new();

        return $clone;
    }

    public function retired(RetirementFactory $retirementFactory, EmploymentFactory $employmentFactory = null): self
    {
        $clone = clone $this;
        $clone->status = TagTeamStatus::RETIRED;
        $clone->retirementFactory = $retirementFactory ?? RetirementFactory::new();
        // We set the employment factory since a wrestler must be employed to retire
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);
        $clone = $clone->withWrestlers($wrestlerFactory ?? $this->wrestlerFactory);

        return $clone;
    }

    public function withWrestlers(WrestlerFactory $wrestlerFactory = null)
    {
        $clone = clone $this;
        $clone->wrestlerFactory = $wrestlerFactory ?? WrestlerFactory::new()->count(2)->bookable();

        return $clone;
    }

    public function create($attributes = [])
    {
        $tagTeam = TagTeam::create($this->resolveAttributes($attributes));

        if ($this->wrestlerFactory) {
            $this->wrestlerFactory->forTagTeam($tagTeam)->create();
        }

        if ($this->employmentFactory) {
            $this->employmentFactory->forTagTeam($tagTeam)->create();
        }

        if ($this->suspensionFactory) {
            $this->suspensionFactory->forTagTeam($tagTeam)->create();
        }

        if ($this->retirementFactory) {
            $this->retirementFactory->forTagTeam($tagTeam)->create();
        }

        $tagTeam->save();

        return $tagTeam;
    }

    protected function resolveAttributes($attributes = [])
    {
        // Allows overriding attributes on the final `create` call
        if (! empty($attributes)) {
            return $attributes;
        }
        // Allows setting attributes on the `::new()` call
        if (! empty($this->attributes)) {
            return $this->attributes;
        }
        // Default if neither of them are used
        /* @var \Faker\Generator $faker */
        $faker = resolve(\Faker\Generator::class);

        return [
            'name'           => $faker->words(2, true),
            'signature_move' => $faker->words(4, true),
            'status'         => TagTeamStatus::PENDING_EMPLOYMENT,
        ];
    }

    public function __clone()
    {
        if ($this->employmentFactory) {
            $this->employmentFactory = clone $this->employmentFactory;
        }

        if ($this->suspensionFactory) {
            $this->suspensionFactory = clone $this->suspensionFactory;
        }

        if ($this->wrestlerFactory) {
            $this->wrestlerFactory = clone $this->wrestlerFactory;
        }

        if ($this->injuryFactory) {
            $this->injuryFactory = clone $this->injuryFactory;
        }

        if ($this->retirementFactory) {
            $this->retirementFactory = clone $this->retirementFactory;
        }
    }
}
