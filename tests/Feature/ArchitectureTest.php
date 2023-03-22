<?php

declare(strict_types=1);

test('it will not use dump, dd or ray')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not()->toBeUsed();

test('controllers')
    ->expect('App\Http\Controllers')
    ->not->toUse('Illuminiate\Http\Request');

test('models')
    ->expect('App\Models')
    ->toOnlyBeUsedOn('App\Repositories')
    ->toOnlyUse('Illuminate\Database');

test('repositories')
    ->expect('App\Repositories')
    ->toOnlyBeUsedOn('App\Http\Controllers')
    ->toOnlyUse('App\Models');
