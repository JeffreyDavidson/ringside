<?php

use App\Actions\TagTeams\EmployAction;
use App\Exceptions\CannotBeEmployedException;
use App\Http\Controllers\TagTeams\EmployController;
use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Models\TagTeam;
use App\Models\Wrestler;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\patch;

beforeEach(function () {
    $wrestlers = Wrestler::factory()->unemployed()->count(2)->create();
    $this->tagTeam = TagTeam::factory()
        ->withCurrentWrestlers($wrestlers)
        ->unemployed()
        ->create();
});

test('invoke calls employ action and redirects', function () {
    actingAs(administrator())
        ->patch(action([EmployController::class], $this->tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    EmployAction::shouldRun($this->tagTeam);
});

test('invoke returns an error message when employing a non employable tag team', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    actingAs(administrator())
        ->from(action([TagTeamsController::class, 'index']))
        ->patch(action([EmployController::class], $tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']))
        ->assertSessionHas('error');

    EmployAction::shouldRun($this->tagTeam)->andThrows(CannotBeEmployedException::class);
});

test('a basic user cannot employ a tag team', function () {
    actingAs(basicUser())
        ->patch(action([EmployController::class], $this->tagTeam))
        ->assertForbidden();
});

test('a guest cannot employ a tag team', function () {
    patch(action([EmployController::class], $this->tagTeam))
        ->assertRedirect(route('login'));
});
