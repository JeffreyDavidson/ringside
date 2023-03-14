<?php

use App\Http\Livewire\TagTeams\TagTeamsList;
use function Pest\Livewire\livewire;

test('it should return correct view', function () {
    livewire(TagTeamsList::class)
        ->assertViewIs('livewire.tagteams.tagteams-list');
});

test('it should pass correct data', function () {
    livewire(TagTeamsList::class)
        ->assertViewHas('tagTeams');
});
