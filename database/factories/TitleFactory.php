<?php

use Carbon\Carbon;
use App\Models\Title;
use Faker\Generator as Faker;

$factory->define(Title::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
    ];
});

$factory->state(Wrestler::class, 'competable', function ($faker) {
    return [
        'status' => TitleStatus::COMPETABLE,
    ];
});

$factory->afterCreatingState(Wrestler::class, 'competable', function ($wrestler) {
    $wrestler->introduce();
});

$factory->state(Title::class, 'pending-introduction', function ($faker) {
    return [
        'introduced_at' => Carbon::tomorrow()->toDateTimeString()
    ];
});

$factory->state(Wrestler::class, 'retired', function ($faker) {
    return [
        'status' => TitleStatus::RETIRED,
    ];
});

$factory->afterCreatingState(Title::class, 'retired', function ($title) {
    $title->retire();
});
