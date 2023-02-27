<?php

use App\Actions\Wrestlers\ReleaseAction;
use App\Actions\Wrestlers\RetireAction;
use App\Exceptions\CannotBeRetiredException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use function Pest\Laravel\mock;

test('it retires a bookable wrestler', function () {
    $wrestler = Wrestler::factory()->bookable()->create();
    dd(Wrestler::count());
    $datetime = now();

    mock(WrestlerRepository::class)
        ->shouldReceive('retire')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturns($wrestler);

    ReleaseAction::shouldRun($wrestler);

    RetireAction::run($wrestler);
});

test('it throws exception trying to retire an unemployed wrestler', function () {
    $wrestler = Wrestler::factory()->unemployed()->create();

    RetireAction::run($wrestler);
})->throws(CannotBeRetiredException::class);

test('it throws exception trying to retire a wrestler with a future employment', function () {
    $wrestler = Wrestler::factory()->withFutureEmployment()->create();

    RetireAction::run($wrestler);
})->throws(CannotBeRetiredException::class);

test('it throws exception trying to retire a retired wrestler', function () {
    $wrestler = Wrestler::factory()->retired()->create();

    RetireAction::run($wrestler);
})->throws(CannotBeRetiredException::class);
