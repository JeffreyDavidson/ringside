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
    $manager->employ();
});

$factory->state(Manager::class, 'pending-employment', function ($faker) {
    return [
        'status' => ManagerStatus::PENDING_EMPLOYMENT,
    ];
});

$factory->afterCreatingState(Manager::class, 'pending-employment', function ($manager) {
    $manager->employ(now()->addWeeks(3)->toDateTimeString());
});

$factory->state(Manager::class, 'retired', function ($faker) {
    return [
        'status' => ManagerStatus::RETIRED,
    ];
});

$factory->afterCreatingState(Manager::class, 'retired', function ($manager) {
    $manager->employ();
    $manager->retire();
});

$factory->state(Manager::class, 'suspended', function ($faker) {
    return [
        'status' => ManagerStatus::SUSPENDED,
    ];
});

$factory->afterCreatingState(Manager::class, 'suspended', function ($manager) {
    $manager->employ();
    $manager->suspend();
});

$factory->state(Manager::class, 'injured', function ($faker) {
    return [
        'status' => ManagerStatus::INJURED,
    ];
});

$factory->afterCreatingState(Manager::class, 'injured', function ($manager) {
    $manager->employ();
    $manager->injure();
});
