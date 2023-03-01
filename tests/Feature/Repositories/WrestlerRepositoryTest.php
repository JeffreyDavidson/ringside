<?php

use App\Data\WrestlerData;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;

test('creates a wrestler without a signature move', function () {
    $data = new WrestlerData('Example Wrestler Name', 70, 220, 'Laraville, New York', null, null);

    $wrestler = app(WrestlerRepository::class)->create($data);

    expect($wrestler)
        ->name->toEqual('Example Wrestler Name')
        ->height->toEqual('70')
        ->weight->toEqual(220)
        ->hometown->toEqual('Laraville, New York')
        ->signature_move->toBeNull();
});

test('creates a wrestler with a signature move', function () {
    $data = new WrestlerData('Example Wrestler Name', 70, 220, 'Laraville, New York', 'Powerbomb', null);

    $wrestler = app(WrestlerRepository::class)->create($data);

    expect($wrestler)
        ->name->toEqual('Example Wrestler Name')
        ->height->toEqual('70')
        ->weight->toEqual(220)
        ->hometown->toEqual('Laraville, New York')
        ->signature_move->toEqual('Powerbomb');
});

test('updates a wrestler with a signature move', function () {
    $wrestler = Wrestler::factory()->create();
    $data = new WrestlerData('Example Wrestler Name', 70, 220, 'Laraville, New York', 'Powerbomb', null);

    $wrestler = app(WrestlerRepository::class)->update($wrestler, $data);

    expect($wrestler)
        ->name->toEqual('Example Wrestler Name')
        ->height->toEqual('70')
        ->weight->toEqual(220)
        ->hometown->toEqual('Laraville, New York')
        ->signature_move->toEqual('Powerbomb');
});

test('deletes a wrestler', function () {
    $wrestler = Wrestler::factory()->create();

    app(WrestlerRepository::class)->delete($wrestler);

    expect($wrestler->fresh())
        ->deleted_at->not->toBeNull();
});

test('restores a wrestler', function () {
    $wrestler = Wrestler::factory()->trashed()->create();

    app(WrestlerRepository::class)->restore($wrestler);

    expect($wrestler->fresh())
        ->deleted_at->toBeNull();
});

test('employ a wrestler', function () {
    $wrestler = Wrestler::factory()->create();

    $wrestler = app(WrestlerRepository::class)->employ($wrestler, now());

    expect($wrestler->fresh())
        ;
});
