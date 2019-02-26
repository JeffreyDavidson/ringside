<?php

use Faker\Generator as Faker;

$factory->define(App\Referee::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'hired_at' => today()->toDateTimeString(),
    ];
});

$factory->afterCreatingState(\App\Referee::class, 'retired', function ($wrestler) {
    $wrestler->retire();
});

$factory->afterCreatingState(\App\Referee::class, 'injured', function ($referee) {
    $referee->injure();
});
