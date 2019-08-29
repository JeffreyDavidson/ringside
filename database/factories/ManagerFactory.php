<?php

use Carbon\Carbon;
use App\Models\Manager;
use App\Enums\ManagerStatus;
use Faker\Generator as Faker;

$factory->define(Manager::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});

$factory->state(Manager::class, 'bookable', function ($faker) {
    return [
        'status' => ManagerStatus::BOOKABLE,
    ];
});

$factory->afterCreatingState(Manager::class, 'bookable', function ($manager) {
    $manager->employments()->create([
        'started_at' => Carbon::yesterday()->toDateTimeString()
    ]);
});

$factory->afterCreatingState(Manager::class, 'pending-introduction', function ($manager) {
    $manager->employments()->create([
        'started_at' => Carbon::tomorrow()->toDateTimeString()
    ]);
});

$factory->state(Manager::class, 'retired', function ($faker) {
    return [
        'status' => ManagerStatus::RETIRED,
    ];
});

$factory->afterCreatingState(Manager::class, 'retired', function ($manager) {
    $manager->employments()->create([
        'started_at' => Carbon::yesterday()->toDateTimeString()
    ]);

    $manager->retire();
});

$factory->state(Manager::class, 'suspended', function ($faker) {
    return [
        'status' => ManagerStatus::SUSPENDED,
    ];
});

$factory->afterCreatingState(Manager::class, 'suspended', function ($manager) {
    $manager->employments()->create([
        'started_at' => Carbon::yesterday()->toDateTimeString()
    ]);

    $manager->suspend();
});

$factory->state(Manager::class, 'injured', function ($faker) {
    return [
        'status' => ManagerStatus::INJURED,
    ];
});

$factory->afterCreatingState(Manager::class, 'injured', function ($manager) {
    $manager->employments()->create([
        'started_at' => Carbon::yesterday()->toDateTimeString()
    ]);

    $manager->injure();
});
