<?php

use App\Http\Controllers\Stables\StablesController;
use App\Http\Requests\Stables\UpdateRequest;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;

test('edit returns a view', function () {
    $stable = Stable::factory()->create();

    $this->actingAs(administrator())
        ->get(action([StablesController::class, 'edit'], $stable))
        ->assertStatus(200)
        ->assertViewIs('stables.edit')
        ->assertViewHas('stable', $stable);
});

test('a basic user cannot view the form for editing a stable', function () {
    $stable = Stable::factory()->create();

    $this->actingAs(basicUser())
        ->get(action([StablesController::class, 'edit'], $stable))
        ->assertForbidden();
});

test('a guest cannot view the form for editing a stable', function () {
    $stable = Stable::factory()->create();

    $this->get(action([StablesController::class, 'edit'], $stable))
        ->assertRedirect(route('login'));
});

test('updates a stable and redirects', function () {
    $stable = Stable::factory()->create([
        'name' => 'Old Stable Name',
    ]);

    $data = UpdateRequest::factory()->create([
        'name' => 'New Stable Name',
        'start_date' => null,
        'wrestlers' => [],
        'tag_teams' => [],
    ]);

    $this->actingAs(administrator())
        ->from(action([StablesController::class, 'edit'], $stable))
        ->patch(action([StablesController::class, 'update'], $stable), $data)
        ->assertValid()
        ->assertRedirect(action([StablesController::class, 'index']));

    expect($stable->fresh())
        ->name->toBe('New Stable Name')
        ->activations->toBeEmpty();
});

test('wrestlers of stable are synced when stable is updated', function () {
    $formerStableWrestlers = Wrestler::factory()->count(2)->create();
    $stable = Stable::factory()
        ->hasAttached($formerStableWrestlers, ['joined_at' => now()->toDateTimeString()])
        ->create();
    $newStableWrestlers = Wrestler::factory()->count(2)->create();

    $data = UpdateRequest::factory()->create([
        'wrestlers' => $newStableWrestlers->modelKeys(),
    ]);

    $this->actingAs(administrator())
        ->from(action([StablesController::class, 'edit'], $stable))
        ->patch(action([StablesController::class, 'update'], $stable), $data)
        ->assertRedirect(action([StablesController::class, 'index']));

    expect($stable->fresh())
        ->wrestlers->toHaveCount(4)
        ->currentWrestlers->toHaveCount(2)
        ->toContain(...$newStableWrestlers)
        ->not->toContain($formerStableWrestlers->modelKeys());
});

test('tag teams of stable are synced when stable is updated', function () {
    $formerStableTagTeams = TagTeam::factory()->count(2)->create();
    $stable = Stable::factory()
        ->hasAttached($formerStableTagTeams, ['joined_at' => now()->toDateTimeString()])
        ->create();
    $newStableTagTeams = TagTeam::factory()->count(2)->create();

    $data = UpdateRequest::factory()->create([
        'tag_teams' => $newStableTagTeams->modelKeys(),
    ]);

    $this->actingAs(administrator())
        ->from(action([StablesController::class, 'edit'], $stable))
        ->patch(action([StablesController::class, 'update'], $stable), $data)
        ->assertRedirect(action([StablesController::class, 'index']));

    expect($stable->fresh())
        ->tagTeams->toHaveCount(4)
        ->currentTagTeams->toHaveCount(2)
        ->pluck('id')->toContain($newStableTagTeams->modelKeys())
        ->not->toContain($formerStableTagTeams->modelKeys());
});

test('a basic user cannot update a stable', function () {
    $stable = Stable::factory()->create();
    $data = UpdateRequest::factory()->create();

    $this->actingAs(basicUser())
        ->patch(action([StablesController::class, 'update'], $stable), $data)
        ->assertForbidden();
});

test('a guest cannot update a stable', function () {
    $stable = Stable::factory()->create();
    $data = UpdateRequest::factory()->create();

    $this->patch(action([StablesController::class, 'update'], $stable), $data)
        ->assertRedirect(route('login'));
});
