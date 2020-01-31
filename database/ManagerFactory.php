<?php

use App\Models\Manager;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Support\Str;
use App\Enums\ManagerStatus;
use Illuminate\Support\Collection;

class ManagerFactory extends BaseFactory
{
    public $attributes = [];
    /** @var EmploymentFactory|null */
    public $employmentFactory;
    /** @var SuspensionFactory|null */
    public $suspensionFactory;
    /** @var InjuryFactory|null */
    public $injuryFactory;
    /** @var RetirementFactory|null */
    public $retirementFactory;
    public $count = 1;

    public function __construct($attributes = [])
    {
        $this->attributes = $this->resolveAttributes($attributes);
    }

    public static function new(array $attributes = [])
    {
        return new static($attributes);
    }

    public function create($attributes = [])
    {
        $manager = Manager::create($this->resolveAttributes($attributes));

        if ($this->employmentFactory) {
            $this->employmentFactory->forManager($manager)->create();
        }

        if ($this->suspensionFactory) {
            $this->suspensionFactory->forManager($manager)->create();
        }

        if ($this->retirementFactory) {
            $this->retirementFactory->forManager($manager)->create();
        }

        if ($this->injuryFactory) {
            $this->injuryFactory->forManager($manager)->create();
        }

        return $manager;
    }

    public function forTagTeam(TagTeam $tagTeam)
    {
        $clone = clone $this;
        $clone->tagTeam = $tagTeam;

        return $clone;
    }

    public function employed(EmploymentFactory $employmentFactory = null)
    {
        $clone = clone $this;
        $clone->employmentFactory = $employmentFactory ?? EmploymentFactory::new();

        return $clone;
    }

    public function bookable(): self
    {
        $clone = clone $this;
        $clone->status = WrestlerStatus::BOOKABLE;
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);
        // We set these to null since a TagTeam cannot be bookable if any of these exist
        $clone->suspensionFactory = null;
        $clone->injuryFactory = null;
        $clone->retirementFactory = null;

        return $clone;
    }

    public function injured(): self
    {
        $clone = clone $this;
        $clone->status = WrestlerStatus::RETIRED;
        $clone->injuryFactory = $retirementFactory ?? InjuryFactory::new();
        // We set the employment factory since a wrestler must be employed to retire
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);

        return $clone;
    }

    public function suspended(): self
    {
        $clone = clone $this;
        $clone->status = WrestlerStatus::SUSPENDED;
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);

        $clone->suspensionFactory = $suspensionFactory ?? $this->suspensionFactory ?? SuspensionFactory::new();

        return $clone;
    }

    public function retired(): self
    {
        $clone = clone $this;
        $clone->status = WrestlerStatus::RETIRED;
        $clone->retirementFactory = $retirementFactory ?? RetirementFactory::new();
        // We set the employment factory since a wrestler must be employed to retire
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);

        return $clone;
    }

    public function count(int $count)
    {
        $clone = clone $this;
        $clone->count = $count;

        return $clone;
    }

    protected function resolveAttributes($attributes = [])
    {
        /* @var \Faker\Generator $faker */
        $faker = resolve(\Faker\Generator::class);

        // These are attributes that should *always* be set
        $defaultAttributes = [
            // $this->status ?? WrestlerStatus::__default::class
        ];

        // These are default attributes that should be set of nothing else is provided
        if (empty($attributes)) {
            $attributes = [
                'name' => $faker->name,
                'height' => $faker->numberBetween(60, 95),
                'weight' => $faker->numberBetween(180, 500),
                'hometown' => $faker->city.', '.$faker->state,
                'signature_move' => Str::title($faker->words(3, true)),
                'status' => WrestlerStatus::PENDING_EMPLOYMENT,
            ];
        }

        return array_merge($defaultAttributes, $attributes);
    }
}
