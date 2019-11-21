<?php

use Carbon\Carbon;
use App\Models\Title;
use App\Enums\TitleStatus;
use Faker\Generator as Faker;

$factory->define(Title::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
    ];
});

$factory->state(Title::class, 'competable', function ($faker) {
    return [
        'status' => TitleStatus::COMPETABLE,
    ];
});

$factory->afterCreatingState(Title::class, 'competable', function ($Title) {
    $Title->introduce();
});

$factory->state(Title::class, 'pending-introduction', function ($faker) {
    return [
        'introduced_at' => Carbon::tomorrow()->toDateTimeString()
    ];
});

$factory->state(Title::class, 'retired', function ($faker) {
    return [
        'status' => TitleStatus::RETIRED,
    ];
});

$factory->afterCreatingState(Title::class, 'retired', function ($title) {
    $title->retire();
});
