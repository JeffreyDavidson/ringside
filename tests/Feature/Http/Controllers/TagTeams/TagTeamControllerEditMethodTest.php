<?php

use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Database\Eloquent\Collection;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->tagTeam = TagTeam::factory()->create();
});

test('edit returns a view', function () {
    actingAs(administrator())
        ->get(action([TagTeamsController::class, 'edit'], $this->tagTeam))
        ->assertStatus(200)
        ->assertViewIs('tagteams.edit')
        ->assertViewHas('tagTeam', $this->tagTeam);
});

test('the correct wrestlers are available to join an editable team', function () {
    $tagTeam = TagTeam::factory()
        ->hasAttached($wrestlerA = Wrestler::factory()->create(['name' => 'Randy Orton']), ['joined_at' => now()])
        ->hasAttached($wrestlerB = Wrestler::factory()->create(['name' => 'Shawn Michaels']), ['joined_at' => now()])
        ->create();

    $unemployedWrestler = Wrestler::factory()->unemployed()->create(['name' => 'Hulk Hogan']);
    $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create(['name' => 'The Rock']);
    $bookableWrestlerNotOnBookableTagTeam = Wrestler::factory()->bookable()->create(['name' => 'Stone Cold Steve Austin']);
    Wrestler::factory()->bookable()->onCurrentTagTeam()->create();
    Wrestler::factory()->injured()->create();
    Wrestler::factory()->suspended()->create();
    Wrestler::factory()->released()->create();
    Wrestler::factory()->retired()->create();

    $wrestlers = (new Collection([$wrestlerA, $wrestlerB, $unemployedWrestler, $futureEmployedWrestler, $bookableWrestlerNotOnBookableTagTeam]));

    mock(WrestlerRepository::class)
        ->shouldReceive('getAvailableWrestlersForExistingTagTeam')
        ->andReturn($wrestlers);

    actingAs(administrator())
        ->get(action([TagTeamsController::class, 'edit'], $tagTeam))
        ->assertStatus(200)
        ->assertViewIs('tagteams.edit')
        ->assertViewHas('wrestlers', function ($data) use ($wrestlers) {
            return $data->keys()->all() == $wrestlers->modelKeys() && count($wrestlers->modelKeys()) === 5;
        })
        ->assertSeeText('Hulk Hogan')
        ->assertSeeText('The Rock')
        ->assertSeeText('Stone Cold Steve Austin')
        ->assertSeeText('Randy Orton')
        ->assertSeeText('Shawn Michaels');
});

test('a basic user cannot view the form for editing a tag team', function () {
    actingAs(basicUser())
        ->get(action([TagTeamsController::class, 'edit'], $this->tagTeam))
        ->assertForbidden();
});

test('a guest cannot view the form for editing a tag team', function () {
    get(action([TagTeamsController::class, 'edit'], $this->tagTeam))
        ->assertRedirect(route('login'));
});
