<?php

use App\Http\Controllers\TagTeams\TagTeamsController;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('index returns a view', function () {
    actingAs(administrator())
        ->get(action([TagTeamsController::class, 'index']))
        ->assertOk()
        ->assertViewIs('tagteams.index');
});

test('a basic user cannot view tag teams index page', function () {
    actingAs(basicUser())
        ->get(action([TagTeamsController::class, 'index']))
        ->assertForbidden();
});

test('a guest cannot view tag teams index page', function () {
    get(action([TagTeamsController::class, 'index']))
        ->assertRedirect(route('login'));
});
