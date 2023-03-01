<?php

use App\Actions\Wrestlers\ReinstateAction;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;

test('it reinstates a suspended wrestler at the current datetime by default', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->suspended()->create();
    $datetime = now();

    mock(WrestlerRepository::class)
        ->shouldReceive('reinstate')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    ReinstateAction::run($wrestler);
});

test('it reinstates a suspended wrestler at a specific datetime', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->suspended()->create();
    $datetime = now()->addDays(2);

    mock(WrestlerRepository::class)
        ->shouldReceive('reinstate')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    ReinstateAction::run($wrestler, $datetime);
});

test('reinstating a suspended wrestler on an unbookable tag team makes tag team bookable', function () {
    $tagTeam = TagTeam::factory()
        ->hasAttached($suspendedWrestler = Wrestler::factory()->suspended()->create())
        ->hasAttached(Wrestler::factory()->bookable())
        ->has(Employment::factory()->started(Carbon::yesterday()))
        ->create();

    $this->actingAs(administrator())
        ->patch(action([ReinstateController::class], $suspendedWrestler));

    expect($tagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::BOOKABLE);
})->skip();

test('invoke throws exception for reinstating a non reinstatable wrestler', function ($factoryState) {
    $this->withoutExceptionHandling();

    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([ReinstateController::class], $wrestler));
})->throws(CannotBeReinstatedException::class)->with([
    'bookable',
    'unemployed',
    'injured',
    'released',
    'withFutureEmployment',
    'retired',
])->skip();
