<?php

namespace Tests\Factories;

use App\Enums\TitleStatus;
use App\Models\Title;
use Christophrumpel\LaravelFactoriesReloaded\BaseFactory;
use Faker\Generator as Faker;

class TitleFactory extends BaseFactory
{
    protected string $modelClass = Title::class;

    public function create(array $extra = []): Title
    {
        return parent::build($extra);
    }

    public function make(array $extra = []): Title
    {
        return parent::build($extra, 'make');
    }

    public function getDefaults(Faker $faker): array
    {
        return [
            'name' => $faker->sentence,
            'status' => TitleStatus::__default,
        ];
    }

    public function active(): TitleFactory
    {
        return tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::ACTIVE,
        ]);
    }

    public function inactive(): TitleFactory
    {
        return tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::INACTIVE,
        ]);
    }

    public function futureActivation(): TitleFactory
    {
        return tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::FUTURE_ACTIVATION,
        ]);
    }

    public function retired(): TitleFactory
    {
        return tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::RETIRED,
        ]);
    }

    public function unactivated(): TitleFactory
    {
        return tap(clone $this)->overwriteDefaults([
            'status' => TitleStatus::UNACTIVATED,
        ]);
    }
}

