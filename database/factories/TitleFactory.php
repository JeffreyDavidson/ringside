<?php

use Carbon\Carbon;
use App\Models\Title;
use App\Enums\TitleStatus;
use Faker\Generator as Faker;

$factory->define(Title::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'status' => TitleStatus::PENDING_INTRODUCTION,
    ];
});

$factory->state(Title::class, 'active', function ($faker) {
    return [
        'status' => TitleStatus::COMPETABLE,
    ];
});

$factory->afterCreatingState(Title::class, 'active', function ($title) {
    $title->introduce();
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
    $title->introduce();
    $title->retire();
});
