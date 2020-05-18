<?php

namespace Tests\Factories;

use App\Enums\TitleStatus;
use App\Models\Title;
use Christophrumpel\LaravelFactoriesReloaded\BaseFactory;
use Faker\Generator as Faker;

class TitleFactory extends BaseFactory
{
    /** @var ActivationFactory|null */
    public $activationFactory;

    /** @var RetirementFactory|null */
    public $retirementFactory;

    /** @var $softDeleted */
    public $softDeleted = false;

    protected string $modelClass = Title::class;

    public function create(array $extra = []): Title
    {
        $title = parent::build($extra);

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
    }

    public function make(array $extra = []): Title
    {
        dd(parent::build($extra, 'make'));
        return parent::build($extra, 'make');
    }

    public function getDefaults(Faker $faker): array
    {
        return [
            'name' => $faker->sentence,
            'status' => TitleStatus::__default,
        ];
    }

    public function active(ActivationFactory $activationFactory = null): TitleFactory
    {
        $clone = tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::ACTIVE,
        ]);

        $clone->activationFactory = $activationFactory ?? ActivationFactory::new()->started(now());

        $clone->retirementFactory = null;

        return $clone;
    }

    public function inactive(): TitleFactory
    {
        return tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::INACTIVE,
        ]);
    }

    public function futureActivation(): TitleFactory
    {
        $clone = tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::FUTURE_ACTIVATION,
        ]);

        $clone->activationFactory = ActivationFactory::new()->started(now()->addDays(4));

        return $clone;
    }

    public function retired(): TitleFactory
    {
        $clone = tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::RETIRED,
        ]);

        $clone->activationFactory = ActivationFactory::new()->started(now()->subMonths(1))->ended(now()->subDays(3));

        $clone->retirementFactory = $retirementFactory ?? RetirementFactory::new()->started(now());

        return $clone;
    }

    public function unactivated(): TitleFactory
    {
        return tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::UNACTIVATED,
        ]);
    }

    public function softDeleted($delete = true)
    {
        $clone = clone $this;
        $clone->softDeleted = $delete;

        return $clone;
    }
}
