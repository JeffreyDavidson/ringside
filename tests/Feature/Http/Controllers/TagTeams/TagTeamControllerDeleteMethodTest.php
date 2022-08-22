<?php

namespace Tests\Feature\Http\Controllers\TagTeams;

use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Models\TagTeam;

test('deletes a tag team and redirects', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->actingAs(administrator())
        ->delete(action([TagTeamsController::class, 'destroy'], $tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    $this->assertSoftDeleted($tagTeam);
});

test('a basic user cannot delete a tag team', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->actingAs(basicUser())
        ->delete(action([TagTeamsController::class, 'destroy'], $tagTeam))
        ->assertForbidden();
});

test('a guest cannot delete a tag team', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->delete(action([TagTeamsController::class, 'destroy'], $tagTeam))
        ->assertRedirect(route('login'));
});
