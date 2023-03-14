<?php

use App\Actions\TagTeams\ReinstateAction;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\TagTeams\ReinstateController;
use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Models\TagTeam;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\patch;

beforeEach(function () {
    $this->tagTeam = TagTeam::factory()->suspended()->create();
});

test('invoke calls reinstate action and redirects', function () {
    actingAs(administrator())
        ->patch(action([ReinstateController::class], $this->tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    ReinstateAction::shouldRun()->with($this->tagTeam);
});

test('a basic user cannot reinstate a suspended tag team', function () {
    actingAs(basicUser())
        ->patch(action([ReinstateController::class], $this->tagTeam))
        ->assertForbidden();
});

test('a guest cannot reinstate a suspended tag team', function () {
    patch(action([ReinstateController::class], $this->tagTeam))
        ->assertRedirect(route('login'));
});

test('invoke returns an error message when reinstating a non reinstatable tag team', function () {
    $tagTeam = TagTeam::factory()->create();

    ReinstateAction::allowToRun()->andThrow(CannotBeReinstatedException::class);

    actingAs(administrator())
        ->from(action([TagTeamsController::class, 'index']))
        ->patch(action([ReinstateController::class], $tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']))
        ->assertSessionHas('error');
});
