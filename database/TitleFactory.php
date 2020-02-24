<?php

use App\Models\Title;
use App\Enums\TitleStatus;
use Illuminate\Support\Str;

class TitleFactory extends BaseFactory
{
    /** @var IntroductionFactory|null */
    public $introductionFactory;
    /** @var RetirementFactory|null */
    public $retirementFactory;
    public $softDeleted = false;
    protected $factoriesToClone = [
        'introductionFactory',
        'retirementFactory',
    ];

    public function pendingIntroduction()
    {
        $clone = clone $this;
        $clone->status = TitleStatus::PENDING_INTRODUCTION;
        $clone->retirementFactory = null;

        return $clone;
    }

    public function introduced(IntroductionFactory $introductionFactory = null)
    {
        $clone = clone $this;
        $clone->introductionFactory = $introductionFactory ?? IntroductionFactory::new();

        return $clone;
    }

    public function available(IntroductionFactory $introductionFactory = null)
    {
        $clone = clone $this;
        $clone->status = TitleStatus::COMPETABLE;
        $clone = $clone->introduced($introductionFactory ?? $this->introductionFactory);
        $clone->retirementFactory = null;

        return $clone;
    }

    public function retired(RetirementFactory $retirementFactory = null, IntroductionFactory $introductionFactory = null)
    {
        $clone = clone $this;
        $clone->status = TitleStatus::RETIRED;
        $clone->retirementFactory = $retirementFactory ?? RetirementFactory::new();
        // We set the introduction factory since a wrestler must be employed to retire
        $clone = $clone->introduced($introductionFactory ?? $this->introductionFactory);

        return $clone;
    }

    public function create($attributes = [])
    {
        return $this->make(function ($attributes) {
            $title = Title::create($this->resolveAttributes($attributes));

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

    protected function defaultAttributes(Faker\Generator $faker)
    {
        return [
            'name' => Str::title($faker->words(2, true)),
            'status' => TitleStatus::__default,
        ];
    }
}
