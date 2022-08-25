<?php

use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Models\TagTeam;

test('edit returns a view', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->actingAs(administrator())
        ->get(action([TagTeamsController::class, 'edit'], $tagTeam))
        ->assertStatus(200)
        ->assertViewIs('tagteams.edit')
        ->assertViewHas('tagTeam', $tagTeam);
});

test('a basic user cannot view the form for editing a tag team', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->actingAs(basicUser())
        ->get(action([TagTeamsController::class, 'edit'], $tagTeam))
        ->assertForbidden();
});

test('a guest cannot view the form for editing a tag team', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->get(action([TagTeamsController::class, 'edit'], $tagTeam))
        ->assertRedirect(route('login'));
});
