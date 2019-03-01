<?php

use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Faker\Generator as Faker;

$factory->define(Stable::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'started_at' => today()->toDateTimeString(),
        'is_active' => true,
    ];
});

$factory->afterCreating(Stable::class, function ($stable, $faker) {
    $stable->wrestlers()->attach(factory(Wrestler::class)->create(['hired_at' => $stable->started_at]));
    $stable->tagteams()->attach(factory(TagTeam::class)->create(['hired_at' => $stable->started_at]));
});

$factory->state(Stable::class, 'active', [
    'is_active' => true,
]);

$factory->afterCreatingState(Stable::class, 'inactive', function ($tagteam) {
    $tagteam->deactivate();
});

$factory->afterCreatingState(Stable::class, 'retired', function ($stable) {
    $stable->retire();
});
