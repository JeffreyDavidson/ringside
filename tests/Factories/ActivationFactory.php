<?php

namespace Tests\Factories;

use App\Models\Activation;
use Christophrumpel\LaravelFactoriesReloaded\BaseFactory;
use Faker\Generator as Faker;

class ActivationFactory extends BaseFactory
{
    protected string $modelClass = Activation::class;

    public function create(array $extra = []): Activation
    {
        return parent::build($extra);
    }

    public function make(array $extra = []): Activation
    {
        return parent::build($extra, 'make');
    }

    public function getDefaults(Faker $faker): array
    {
        return [];
    }
}

