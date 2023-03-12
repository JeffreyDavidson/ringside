<?php

use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use Illuminate\Database\Eloquent\Collection;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\mock;

test('create returns a view', function () {
    actingAs(administrator())
        ->get(action([TagTeamsController::class, 'create']))
        ->assertStatus(200)
        ->assertViewIs('tagteams.create')
        ->assertViewHas('wrestlers');
});

test('the correct wrestlers are available to join a new tag team', function () {
    $unemployedWrestler = Wrestler::factory()->unemployed()->create(['name' => 'Hulk Hogan']);
    $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create(['name' => 'The Rock']);
    $bookableWrestlerNotOnBookableTagTeam = Wrestler::factory()->bookable()->create(['name' => 'Stone Cold Steve Austin']);
    Wrestler::factory()->bookable()->onCurrentTagTeam()->create();
    Wrestler::factory()->injured()->create();
    Wrestler::factory()->suspended()->create();
    Wrestler::factory()->released()->create();
    Wrestler::factory()->retired()->create();

    $wrestlers = (new Collection([$unemployedWrestler, $futureEmployedWrestler, $bookableWrestlerNotOnBookableTagTeam]));

    mock(WrestlerRepository::class)
        ->shouldReceive('getAvailableWrestlersForNewTagTeam')
        ->andReturn($wrestlers);

    actingAs(administrator())
        ->get(action([TagTeamsController::class, 'create']))
        ->assertStatus(200)
        ->assertViewIs('tagteams.create')
        ->assertViewHas('wrestlers', fn ($data) => $data->keys()->all() == $wrestlers->modelKeys())
        ->assertSeeText('Hulk Hogan')
        ->assertSeeText('The Rock')
        ->assertSeeText('Stone Cold Steve Austin');
});

test('a basic user cannot view the form for creating a tag team', function () {
    actingAs(basicUser())
        ->get(action([TagTeamsController::class, 'create']))
        ->assertForbidden();
});

test('a guest cannot view the form for creating a tag team', function () {
    get(action([TagTeamsController::class, 'create']))
        ->assertRedirect(route('login'));
});
