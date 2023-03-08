<?php

use App\Actions\TagTeams\DeleteAction;
use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Models\TagTeam;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

beforeEach(function () {
    $this->tagTeam = TagTeam::factory()->create();
});

test('destroy calls delete action and redirects', function () {
    actingAs(administrator())
        ->delete(action([TagTeamsController::class, 'destroy'], $this->tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    DeleteAction::shouldRun()->with($this->tagTeam);
});

test('a basic user cannot delete a tag team', function () {
    actingAs(basicUser())
        ->delete(action([TagTeamsController::class, 'destroy'], $this->tagTeam))
        ->assertForbidden();
});

test('a guest cannot delete a tag team', function () {
    delete(action([TagTeamsController::class, 'destroy'], $this->tagTeam))
        ->assertRedirect(route('login'));
});
