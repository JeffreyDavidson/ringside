<?php

use App\Actions\TagTeams\UpdateAction;
use App\Data\TagTeamData;
use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Http\Requests\TagTeams\UpdateRequest;
use App\Models\TagTeam;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\patch;

beforeEach(function () {
    $this->tagTeam = TagTeam::factory()->create();
    $this->data = UpdateRequest::factory()->create();
    $this->request = UpdateRequest::create(action([TagTeamsController::class, 'update'], $this->tagTeam), 'PATCH', $this->data);
});

test('update calls update action and redirects', function () {
    actingAs(administrator())
        ->from(action([TagTeamsController::class, 'edit'], $this->tagTeam))
        ->patch(action([TagTeamsController::class, 'update'], $this->tagTeam), $this->data)
        ->assertValid()
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    UpdateAction::shouldRun()->with($this->tagTeam, TagTeamData::fromUpdateRequest($this->request));
});

test('a basic user cannot update a tag team', function () {
    actingAs(basicUser())
        ->patch(action([TagTeamsController::class, 'update'], $this->tagTeam), $this->data)
        ->assertForbidden();
});

test('a guest cannot update a tag team', function () {
    patch(action([TagTeamsController::class, 'update'], $this->tagTeam), $this->data)
        ->assertRedirect(route('login'));
});
