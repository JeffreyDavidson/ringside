<?php

use App\Enums\TitleStatus;
use App\Models\Title;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TitleFactory extends BaseFactory
{
    /** @var RetirementFactory|null */
    public $retirementFactory;
    public $softDeleted = false;
    protected $factoriesToClone = [
        'retirementFactory',
    ];

    public function pendingIntroduction()
    {
        $clone = clone $this;
        $clone->attributes['status'] = TitleStatus::PENDING_INTRODUCTION;
        $clone->retirementFactory = null;

        return $clone;
    }

    public function introduced()
    {
        $clone = clone $this;
        $clone->attributes['introduced_at'] = Carbon::yesterday()->toDateTimeString();

        return $clone;
    }

    public function competable()
    {
        $clone = clone $this;
        $clone->attributes['status'] = TitleStatus::COMPETABLE;
        $clone = $clone->introduced();
        $clone->retirementFactory = null;

        return $clone;
    }

    public function retired(RetirementFactory $retirementFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['status'] = TitleStatus::RETIRED;
        $clone = $clone->introduced();
        $clone->retirementFactory = $retirementFactory ?? RetirementFactory::new();

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
