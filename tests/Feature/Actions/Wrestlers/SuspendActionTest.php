<?php

use App\Actions\Wrestlers\SuspendAction;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;

test('it suspends a bookable wrestler at the current datetime by default', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->bookable()->create();
    $datetime = now();

    mock(WrestlerRepository::class)
        ->shouldReceive('suspend')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    SuspendAction::run($wrestler);
});

test('it suspends a bookable wrestler at a specific datetime', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->bookable()->create();
    $datetime = now()->addDays(2);

    mock(WrestlerRepository::class)
        ->shouldReceive('suspend')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    SuspendAction::run($wrestler, $datetime);
});

test('suspending a bookable wrestler on a bookable tag team makes tag team unbookable', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->currentWrestlers()->first();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $wrestler));

    expect($tagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::UNBOOKABLE);
})->skip();

test('invoke throws exception for suspending a non suspendable wrestler', function ($factoryState) {
    $this->withoutExceptionHandling();

    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $wrestler));
})->throws(CannotBeSuspendedException::class)->with([
    'unemployed',
    'withFutureEmployment',
    'injured',
    'released',
    'retired',
    'suspended',
])->skip();
