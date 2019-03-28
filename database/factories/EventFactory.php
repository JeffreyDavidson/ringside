<?php

use App\Models\Event;
use App\Models\Venue;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'date' => $faker->dateTime(),
        'venue_id' => function () {
            return factory(Venue::class)->create()->id;
        },
        'preview' => $faker->paragraph(),
    ];
});
