<?php

use Carbon\Carbon;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Faker\Generator as Faker;

$factory->define(Stable::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
    ];
});


$factory->afterCreatingState(Stable::class, 'bookable', function ($stable) {
    $startedAt = Carbon::yesterday()->toDateTimeString();

    $stable->employments()->create([
        'started_at' => $startedAt
    ]);

    $stable->wrestlers()->attach(factory(Wrestler::class)->states('bookable')->create(), ['joined_at' => $startedAt]);
    $stable->tagteams()->attach(factory(TagTeam::class)->states('bookable')->create(), ['joined_at' => $startedAt]);
});

$factory->afterCreatingState(Stable::class, 'pending-introduction', function ($stable) {
    $startedAt = Carbon::tomorrow()->toDateTimeString();

    $stable->employments()->create([
        'started_at' => $startedAt
    ]);

    $stable->wrestlers()->attach(factory(Wrestler::class)->states('bookable')->create(), ['joined_at' => $startedAt]);
    $stable->tagteams()->attach(factory(TagTeam::class)->states('bookable')->create(), ['joined_at' => $startedAt]);
});


$factory->afterCreatingState(Stable::class, 'retired', function ($stable) {
    $startedAt = Carbon::yesterday()->toDateTimeString();

    $stable->employments()->create([
        'started_at' => Carbon::yesterday()->toDateTimeString()
    ]);

    $stable->wrestlers()->attach(factory(Wrestler::class)->states('bookable')->create(), ['joined_at' => $startedAt]);
    $stable->tagteams()->attach(factory(TagTeam::class)->states('bookable')->create(), ['joined_at' => $startedAt]);

    $stable->retire();
});
