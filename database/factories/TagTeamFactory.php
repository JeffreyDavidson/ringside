<?php

use Carbon\Carbon;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Enums\TagTeamStatus;
use Faker\Generator as Faker;

$factory->define(TagTeam::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'signature_move' => $faker->words(4, true),
    ];
});

$factory->afterCreating(TagTeam::class, function ($tagteam) {
    $tagteam->addWrestlers(factory(Wrestler::class, 2)->states('bookable')->create()->modelKeys());
});

$factory->state(TagTeam::class, 'bookable', function ($faker) {
    return [
        'status' => TagTeamStatus::BOOKABLE,
    ];
});

$factory->afterCreatingState(TagTeam::class, 'bookable', function ($tagteam) {
    $tagteam->employ();
});

$factory->state(TagTeam::class, 'pending-employment', function ($faker) {
    return [
        'status' => TagTeamStatus::PENDING_EMPLOYMENT,
    ];
});

$factory->afterCreatingState(TagTeam::class, 'pending-employment', function ($tagteam) {
    $tagteam->employ(Carbon::tomorrow()->toDateTimeString());
});

$factory->state(TagTeam::class, 'suspended', function ($faker) {
    return [
        'status' => TagTeamStatus::SUSPENDED,
    ];
});

$factory->afterCreatingState(TagTeam::class, 'suspended', function ($tagteam) {
    $tagteam->employ();
    $tagteam->suspend();
});

$factory->state(TagTeam::class, 'retired', function ($faker) {
    return [
        'status' => TagTeamStatus::RETIRED,
    ];
});

$factory->afterCreatingState(TagTeam::class, 'retired', function ($tagteam) {
    $tagteam->employ();
    $tagteam->retire();
});
