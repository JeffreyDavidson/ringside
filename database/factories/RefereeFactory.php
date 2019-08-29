<?php

use Carbon\Carbon;
use App\Models\Referee;
use App\Enums\RefereeStatus;
use Faker\Generator as Faker;

$factory->define(Referee::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});

$factory->state(Referee::class, 'bookable', function ($faker) {
    return [
        'status' => RefereeStatus::BOOKABLE,
    ];
});

$factory->afterCreatingState(Referee::class, 'bookable', function ($referee) {
    $referee->employments()->create([
        'started_at' => Carbon::yesterday()->toDateTimeString()
    ]);
});

$factory->state(Referee::class, 'retired', function ($faker) {
    return [
        'status' => RefereeStatus::RETIRED,
    ];
});

$factory->afterCreatingState(Referee::class, 'retired', function ($referee) {
    $referee->employments()->create([
        'started_at' => Carbon::yesterday()->toDateTimeString()
    ]);

    $referee->retire();
});

$factory->state(Referee::class, 'injured', function ($faker) {
    return [
        'status' => RefereeStatus::INJURED,
    ];
});

$factory->afterCreatingState(Referee::class, 'injured', function ($referee) {
    $referee->employments()->create([
        'started_at' => Carbon::yesterday()->toDateTimeString()
    ]);

    $referee->injure();
});

$factory->state(Referee::class, 'suspended', function ($faker) {
    return [
        'status' => RefereeStatus::SUSPENDED,
    ];
});

$factory->afterCreatingState(Referee::class, 'suspended', function ($referee) {
    $referee->employments()->create([
        'started_at' => Carbon::yesterday()->toDateTimeString()
    ]);

    $referee->suspend();
});

$factory->state(Referee::class, 'pending-introduction', function ($faker) {
    return [
        'status' => RefereeStatus::PENDING_INTRODUCTION,
    ];
});

$factory->afterCreatingState(Referee::class, 'pending-introduction', function ($referee) {
    $referee->employments()->create([
        'started_at' => Carbon::tomorrow()->toDateTimeString()
    ]);
});
