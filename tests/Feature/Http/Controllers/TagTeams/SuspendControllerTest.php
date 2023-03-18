<?php

use App\Actions\TagTeams\SuspendAction;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\TagTeams\SuspendController;
use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Models\TagTeam;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\patch;

beforeEach(function () {
    $this->tagTeam = TagTeam::factory()->bookable()->create();
});

test('invoke calls suspend action and redirects', function () {
    actingAs(administrator())
        ->patch(action([SuspendController::class], $this->tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    SuspendAction::shouldRun()->with($this->tagTeam);
});

test('a basic user cannot retire a bookable tag team', function () {
    actingAs(basicUser())
        ->patch(action([SuspendController::class], $this->tagTeam))
        ->assertForbidden();
});

test('a guest cannot suspend a bookable tag team', function () {
    patch(action([SuspendController::class], $this->tagTeam))
        ->assertRedirect(route('login'));
});

test('it returns an error when an exception is thrown', function () {
    $tagTeam = TagTeam::factory()->create();

    SuspendAction::allowToRun()->andThrow(CannotBeSuspendedException::class);

    actingAs(administrator())
        ->from(action([TagTeamsController::class, 'index']))
        ->patch(action([SuspendController::class], $tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']))
        ->assertSessionHas('error');
});
