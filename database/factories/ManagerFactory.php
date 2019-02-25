<?php

use Faker\Generator as Faker;

$factory->define(\App\Manager::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'hired_at' => $faker->dateTime(),
    ];
});

$factory->afterCreatingState(\App\Manager::class, 'retired', function ($manager) {
    $manager->retire();
});
