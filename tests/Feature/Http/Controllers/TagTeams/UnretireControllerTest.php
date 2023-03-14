<?php

use App\Actions\TagTeams\UnretireAction;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Http\Controllers\TagTeams\UnretireController;
use App\Models\TagTeam;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\patch;

beforeEach(function () {
    $this->tagTeam = TagTeam::factory()->retired()->create();
});

test('invoke calls unretire action and redirects', function () {
    actingAs(administrator())
        ->patch(action([UnretireController::class], $this->tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    UnretireAction::shouldRun()->with($this->tagTeam);
});

test('a basic user cannot unretire a tag team', function () {
    actingAs(basicUser())
        ->patch(action([UnretireController::class], $this->tagTeam))
        ->assertForbidden();
});

test('a guest cannot unretire a tag team', function () {
    patch(action([UnretireController::class], $this->tagTeam))
        ->assertRedirect(route('login'));
});

test('invoke returns an error message when suspending a non suspendable tag team', function () {
    $tagTeam = TagTeam::factory()->create();

    UnretireAction::allowToRun()->andThrow(CannotBeUnretiredException::class);

    actingAs(administrator())
        ->from(action([TagTeamsController::class, 'index']))
        ->patch(action([UnretireController::class], $tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']))
        ->assertSessionHas('error');
});
