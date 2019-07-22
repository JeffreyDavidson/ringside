<?php

use \App\Models\Manager;
use Faker\Generator as Faker;

$factory->define(Manager::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});


$factory->afterCreatingState(Manager::class, 'retired', function ($manager) {
    $manager->retire();
});

$factory->afterCreatingState(Manager::class, 'suspended', function ($manager) {
    $manager->suspend();
});

$factory->afterCreatingState(Manager::class, 'injured', function ($manager) {
    $manager->injure();
});

$factory->afterCreatingState(Manager::class, 'inactive', function ($manager) {
    $manager->deactivate();
});
