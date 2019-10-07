<?php

use Carbon\Carbon;
use App\Models\Referee;
use App\Enums\RefereeStatus;
use Faker\Generator as Faker;

$factory->define(Referee::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'status' => RefereeStatus::PENDING_EMPLOYMENT,
    ];
});

$factory->state(Referee::class, 'bookable', function ($faker) {
    return [
        'status' => RefereeStatus::BOOKABLE,
    ];
});

$factory->afterCreatingState(Referee::class, 'bookable', function ($referee) {
    $referee->employ();
});

$factory->state(Referee::class, 'pending-employment', function ($faker) {
    return [
        'status' => RefereeStatus::PENDING_EMPLOYMENT,
    ];
});

$factory->afterCreatingState(Referee::class, 'pending-employment', function ($referee) {
    $referee->employ(Carbon::tomorrow()->toDateTimeString());
});

$factory->state(Referee::class, 'retired', function ($faker) {
    return [
        'status' => RefereeStatus::RETIRED,
    ];
});

$factory->afterCreatingState(Referee::class, 'retired', function ($referee) {
    $referee->employ(Carbon::yesterday());
    $referee->retire();
});

$factory->state(Referee::class, 'suspended', function ($faker) {
    return [
        'status' => RefereeStatus::SUSPENDED,
    ];
});

$factory->afterCreatingState(Referee::class, 'suspended', function ($referee) {
    $referee->employ(Carbon::yesterday());
    $referee->suspend();
});

$factory->state(Referee::class, 'injured', function ($faker) {
    return [
        'status' => RefereeStatus::INJURED,
    ];
});

$factory->afterCreatingState(Referee::class, 'injured', function ($referee) {
    $referee->employ(Carbon::yesterday());
    $referee->injure();
});
