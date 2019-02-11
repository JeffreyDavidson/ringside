<?php

use Faker\Generator as Faker;

$factory->define(\App\Wrestler::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'height' => $faker->randomNumber(),
        'weight' => $faker->randomNumber(),
        'hometown' => $faker->city .', '.$faker->state,
        'hired_at' => $faker->dateTime(),
    ];
});

$factory->afterCreatingState(\App\Wrestler::class, 'retired', function ($wrestler) {
    $wrestler->retire();
});
