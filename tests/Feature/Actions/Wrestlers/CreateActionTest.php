<?php

use App\Actions\Wrestlers\CreateAction;
use App\Actions\Wrestlers\EmployAction;
use App\Data\WrestlerData;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;
use Illuminate\Support\Carbon;

test('it creates a wrestler', function () {
    $data = new WrestlerData('Example Wrestler Name', 70, 220, 'Laraville, New York', null, null);

    mock(WrestlerRepository::class)
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturns(new Wrestler());

    CreateAction::run($data);
});

test('it creates a wrestler with a signature move and redirects', function () {
    $data = new WrestlerData('Example Wrestler Name', 70, 220, 'Laraville, New York', 'Signature Move Example', null);

    mock(WrestlerRepository::class)
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturns(new Wrestler());

    CreateAction::run($data);
});

test('an employment is created for the wrestler if start date is filled in request', function () {
    testTime()->freeze();
    $dateTime = Carbon::now();
    $data = new WrestlerData('Example Wrestler Name', 70, 220, 'Laraville, New York', null, $dateTime);

    EmployAction::mock()->shouldReceive('handle')->with($wrestler, $data->start_date);

    CreateAction::run($data);
});
