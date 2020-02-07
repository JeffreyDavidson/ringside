<?php

use App\Enums\TagTeamStatus;
use App\Models\TagTeam;
use Illuminate\Database\Eloquent\Collection;
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
    protected $factoriesToClone = [
        'employmentFactory',
        'suspensionFactory',
        'wrestlerFactory',
        'retirementFactory',
    ];

    public function pendingEmployment()
    {
        $clone = clone $this;
        $clone->status = TagTeamStatus::PENDING_EMPLOYMENT;
        // We set these to null since we can't be pending employment if they're set
        $clone->employmentFactory = null;
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

    public function create($attributes = [])
    {
        if ($this->count > 1) {
            $created = new Collection();
            for ($i = 0; $i < $this->count; $i++) {
                $clone = clone $this;
                $clone->count = 1;
                $created->push($clone->create($attributes));
            }

            return $created;
        }

        // dd($this->resolveAttributes($attributes));
        $tagTeam = TagTeam::create($this->resolveAttributes($attributes));

        if ($this->employmentFactory) {
            $this->employmentFactory->forTagTeam($tagTeam)->create();
        }

        if ($this->wrestlerFactory) {
            $this->wrestlerFactory->forTagTeam($tagTeam)->create();
            // dd($tagTeam->currentWrestlers);
            // dd($tagTeam->wrestlerHistory);
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

    protected function defaultAttributes(Faker\Generator $faker)
    {
        return [
            'name'           => $faker->words(2, true),
            'signature_move' => Str::title($faker->words(3, true)),
            'status'         => TagTeamStatus::PENDING_EMPLOYMENT,
        ];
    }
}
