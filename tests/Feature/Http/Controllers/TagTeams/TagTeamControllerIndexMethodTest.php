<?php

declare(strict_types=1);

use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Livewire\TagTeams\Tables\TagTeamsTable;

test('index returns a view', function () {
    $this->actingAs(administrator())
        ->get(action([TagTeamsController::class, 'index']))
        ->assertOk()
        ->assertViewIs('tag-teams.index')
        ->assertSeeLivewire(TagTeamsTable::class);
});

test('a basic user cannot view tag teams index page', function () {
    $this->actingAs(basicUser())
        ->get(action([TagTeamsController::class, 'index']))
        ->assertForbidden();
});

test('a guest cannot view tag teams index page', function () {
    $this->get(action([TagTeamsController::class, 'index']))
        ->assertRedirect(route('login'));
});
