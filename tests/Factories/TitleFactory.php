<?php

namespace Tests\Factories;

use App\Enums\TitleStatus;
use App\Models\Title;
use Faker\Generator;
use Illuminate\Support\Str;

class TitleFactory extends BaseFactory
{
    /** @var ActivationFactory|null */
    public $activationFactory;
    /** @var RetirementFactory|null */
    public $retirementFactory;
    public $softDeleted = false;
    protected $factoriesToClone = [
        'activationFactory',
        'retirementFactory',
    ];

    public function pendingActivation(ActivationFactory $activationFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['status'] = TitleStatus::PENDING_ACTIVATION;
        $clone->activationFactory = $activationFactory ?? ActivationFactory::new()->started(now()->addDays(2));
        $clone->retirementFactory = null;

        return $clone;
    }

    public function unactivated()
    {
        $clone = clone $this;
        $clone->attributes['status'] = TItleStatus::UNACTIVATED;
        $clone->employmentFactory = null;

        return $clone;
    }

    public function active(ActivationFactory $activationFactory = null)
    {
        $clone = clone $this;
        $clone->activationFactory = $activationFactory ?? ActivationFactory::new()->started(now());
        $clone->retirementFactory = null;

        return $clone;
    }

    public function inactive(ActivationFactory $activationFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['status'] = TitleStatus::INACTIVE;
        $clone->activationFactory = $activationFactory ?? ActivationFactory::new()->started(now()->subMonths(3))->ended(now()->subDay(1));
        $clone->retirementFactory = null;

        return $clone;
    }

    public function retired(RetirementFactory $retirementFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['status'] = TitleStatus::RETIRED;
        $clone->activationFactory = ActivationFactory::new()->started(now()->subMonths(1))->ended(now()->subDays(3));
        $clone->retirementFactory = $retirementFactory ?? RetirementFactory::new()->started(now()->subDays(3));

        return $clone;
    }

    public function create($attributes = [])
    {
        return $this->make(function ($attributes) {
            $title = Title::create($this->resolveAttributes($attributes));

            if ($this->activationFactory) {
                $this->activationFactory->forTitle($title)->create();
            }

            if ($this->retirementFactory) {
                $this->retirementFactory->forTitle($title)->create();
            }

            $title->save();

            if ($this->softDeleted) {
                $title->delete();
            }

            return $title;
        }, $attributes);
    }

    protected function defaultAttributes(Generator $faker)
    {
        return [
            'name' => Str::title($faker->words(2, true). ' Title'),
            'status' => TitleStatus::__default,
        ];
    }
}
