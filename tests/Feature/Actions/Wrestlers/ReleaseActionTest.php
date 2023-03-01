<?php

use App\Actions\Wrestlers\ReleaseAction;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;

test('it releases an employed wrestler at the current datetime by default', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->bookable()->create();
    $datetime = now();

    mock(WrestlerRepository::class)
        ->shouldReceive('release')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    ReleaseAction::run($wrestler);
});

test('it releases an employed wrestler at a specific datetime', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->bookable()->create();
    $datetime = now()->addDays(2);

    mock(WrestlerRepository::class)
        ->shouldReceive('release')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    ReleaseAction::run($wrestler, $datetime);
});

test('releasing a bookable wrestler on a bookable tag team makes tag team unbookable', function () {
    $this->withoutExceptionHandling();

    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->currentWrestlers()->first();

    $this->actingAs(administrator())
        ->patch(action([ReleaseController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect($tagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::UNBOOKABLE);
})->skip();

test('invoke throws an exception for releasing a non releasable wrestler', function ($factoryState) {
    $this->withoutExceptionHandling();

    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([ReleaseController::class], $wrestler));
})->throws(CannotBeReleasedException::class)->with([
    'unemployed',
    'withFutureEmployment',
    'released',
    'retired',
])->skip();
