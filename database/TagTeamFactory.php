<?php

use App\Enums\TagTeamStatus;
use App\Models\TagTeam;
use Illuminate\Support\Str;

class TagTeamFactory extends BaseFactory
{
    /** @var EmploymentFactory|null */
    public $employmentFactory;
    /** @var SuspensionFactory|null */
    public $suspensionFactory;
    /** @var WrestlerFactory|null */
    public $wrestlerFactory;
    /** @var RetirementFactory|null */
    public $retirementFactory;
    public $existingWrestlers;
    public $softDelete = false;
    protected $factoriesToClone = [
        'employmentFactory',
        'suspensionFactory',
        'wrestlerFactory',
        'retirementFactory',
    ];

    public function pendingEmployment(EmploymentFactory $employmentFactory = null)
    {
        $clone = clone $this;
        $clone->status = TagTeamStatus::PENDING_EMPLOYMENT;
        // We set these to null since we can't be pending employment if they're set
        $clone->employmentFactory = $employmentFactory ?? EmploymentFactory::new()->started(now()->addDays(2));
        $clone->suspensionFactory = null;
        $clone->retirementFactory = null;

        return $clone;
    }

    public function employed(EmploymentFactory $employmentFactory = null)
    {
        $clone = clone $this;
        $clone->employmentFactory = $employmentFactory ?? EmploymentFactory::new();

        return $clone;
    }

    public function bookable(EmploymentFactory $employmentFactory = null, WrestlerFactory $wrestlerFactory = null)
    {
        $clone = clone $this;
        $clone->status = TagTeamStatus::BOOKABLE;
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);
        $clone = $clone->withWrestlers($wrestlerFactory ?? $this->wrestlerFactory);
        // We set these to null since a TagTeam cannot be bookable if any of these exist
        $clone->suspensionFactory = null;
        $clone->retirementFactory = null;

        return $clone;
    }

    public function suspended(SuspensionFactory $suspensionFactory = null, EmploymentFactory $employmentFactory = null)
    {
        $clone = clone $this;
        $clone->status = TagTeamStatus::SUSPENDED;
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);
        $clone = $clone->withWrestlers($wrestlerFactory ?? $this->wrestlerFactory);

        $clone->suspensionFactory = $suspensionFactory ?? $this->suspensionFactory ?? SuspensionFactory::new();

        return $clone;
    }

    public function retired(RetirementFactory $retirementFactory = null, EmploymentFactory $employmentFactory = null)
    {
        $clone = clone $this;
        $clone->status = TagTeamStatus::RETIRED;
        $clone = $clone->employed($employmentFactory ?? $this->employmentFactory);
        $clone = $clone->withWrestlers($wrestlerFactory ?? $this->wrestlerFactory);

        $clone->retirementFactory = $retirementFactory ?? RetirementFactory::new();

        return $clone;
    }

    public function withWrestlers(WrestlerFactory $wrestlerFactory = null)
    {
        $clone = clone $this;
        $clone->wrestlerFactory = $wrestlerFactory ?? WrestlerFactory::new()->count(2)->bookable();

        return $clone;
    }

    public function withExistingWrestlers(array $wrestlers)
    {
        return $this->withClone(function ($factory) use ($wrestlers) {
            $factory->wrestlerFactory = null;
            $factory->existingWrestlers = $wrestlers;
        });
    }

    public function create($attributes = [])
    {
        return $this->make(function ($attributes) {
            $tagTeam = TagTeam::create($this->resolveAttributes($attributes));

            if ($this->employmentFactory) {
                $this->employmentFactory->forTagTeam($tagTeam)->create();
            }

            if ($this->wrestlerFactory) {
                $this->wrestlerFactory->forTagTeam($tagTeam)->create();
            }

            if ($this->suspensionFactory) {
                $this->suspensionFactory->forTagTeam($tagTeam)->create();
            }

            if ($this->retirementFactory) {
                $this->retirementFactory->forTagTeam($tagTeam)->create();
            }

            $tagTeam->save();

            if ($this->existingWrestlers) {
                foreach ($this->existingWrestlers as $wrestler) {
                    $wrestler->tagTeamHistory()->attach($tagTeam);
                }
            }

            if ($this->softDelete) {
                $tagTeam->delete();
            }

            return $tagTeam;
        }, $attributes);
    }

    protected function defaultAttributes(Faker\Generator $faker)
    {
        return [
            'name'           => $faker->words(2, true),
            'signature_move' => Str::title($faker->words(3, true)),
            'status'         => TagTeamStatus::PENDING_EMPLOYMENT,
        ];
    }
}
