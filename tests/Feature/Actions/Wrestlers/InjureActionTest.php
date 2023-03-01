<?php

use App\Actions\Wrestlers\InjureAction;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;

test('it injures a bookable wrestler at the current datetime by default', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->bookable()->create();
    $datetime = now();

    mock(WrestlerRepository::class)
        ->shouldReceive('injure')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    InjureAction::run($wrestler);
});

test('it injures a bookable wrestler at a specific datetime', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->bookable()->create();
    $datetime = now()->addDays(2);

    mock(WrestlerRepository::class)
        ->shouldReceive('injure')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    InjureAction::run($wrestler, $datetime);
});

test('injuring a bookable wrestler on a bookable tag team makes tag team unbookable', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->currentWrestlers()->first();

    InjureAction::run($wrestler);

    expect($wrestler->currentTagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::UNBOOKABLE);
})->skip();

test('invoke throws exception for injuring a non injurable wrestler', function ($factoryState) {
    $this->withoutExceptionHandling();

    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    InjureAction::run($wrestler);
})->throws(CannotBeInjuredException::class)->with([
    'unemployed',
    'suspended',
    'released',
    'withFutureEmployment',
    'retired',
    'injured',
])->skip();
