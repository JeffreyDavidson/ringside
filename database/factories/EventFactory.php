<?php

use Carbon\Carbon;
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

$factory->state(Event::class, 'scheduled', function ($faker) {
    return [
        'date' => Carbon::tomorrow()->toDateTimeString(),
    ];
});

$factory->state(Event::class, 'past', function ($faker) {
    return [
        'date' => Carbon::yesterday()->toDateTimeString(),
    ];
});
