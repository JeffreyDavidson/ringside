<?php

use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Http\Requests\TagTeams\StoreRequest;
use App\Models\Employment;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use Illuminate\Support\Carbon;
use function Spatie\PestPluginTestTime\testTime;

test('create returns a view', function () {
    $this->actingAs(administrator())
        ->get(action([TagTeamsController::class, 'create']))
        ->assertStatus(200)
        ->assertViewIs('tagteams.create')
        ->assertViewHas('wrestlers');
});

test('create returns a view2', function () {
    $unemployedWrestler = Wrestler::factory()->unemployed()->create();
    $futureEmployedWrestler = Wrestler::factory()->withFutureEmployment()->create();
    $bookableWrestlerNotOnBookableTagTeam = Wrestler::factory()->bookable()->create();
    // $bookableWrestlerOnBookableTagTeam = Wrestler::factory()->bookable()->hasAttached($tagTeam, ['joined_at' => now()])->create(['current_tag_team_id' => $tagTeam->id]);
    $bookableWrestlerOnBookableTagTeam = Wrestler::factory()->bookable()->onCurrentTagTeam()->create();
    dd($bookableWrestlerOnBookableTagTeam->currentTagTeam);
    $injuredWrestlerNotOnTagTeam = Wrestler::factory()->injured()->create();
    $suspendedWrestlerNotOnTagTeam = Wrestler::factory()->suspended()->create();
    $releasedWrestler = Wrestler::factory()->released()->create();
    $retiredWrestler = Wrestler::factory()->retired()->create();

    $wrestlersAbleToBeAddedToNewTagTeam = WrestlerRepository::getAvailableWrestlersForNewTagTeam()->pluck('name', 'id');

    $this->actingAs(administrator())
        ->get(action([TagTeamsController::class, 'create']))
        ->assertStatus(200)
        ->assertViewIs('tagteams.create')
        ->assertViewHas('wrestlers', $wrestlersAbleToBeAddedToNewTagTeam)
        ->assertViewHas('tagTeam', new TagTeam);
});

test('a basic user cannot view the form for creating a tag team', function () {
    $this->actingAs(basicUser())
        ->get(action([TagTeamsController::class, 'create']))
        ->assertForbidden();
});

test('a guest cannot view the form for creating a tag team', function () {
    $this->get(action([TagTeamsController::class, 'create']))
        ->assertRedirect(route('login'));
});

test('store creates a tag team and redirects', function () {
    $data = StoreRequest::factory()->create([
        'name' => 'Example Tag Team Name',
        'signature_move' => null,
        'start_date' => null,
        'wrestlerA' => null,
        'wrestlerB' => null,
    ]);

    $this->actingAs(administrator())
        ->from(action([TagTeamsController::class, 'create']))
        ->post(action([TagTeamsController::class, 'store']), $data)
        ->assertValid()
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    expect(TagTeam::latest()->first())
        ->name->toBe('Example Tag Team Name')
        ->signature_move->toBeNull()
        ->employments->toBeEmpty()
        ->wrestlers->toBeEmpty();
});

test('an employment is created only for the tag team if start date is filled in request and wrestlers already have active employment', function () {
    testTime()->freeze($startDate = Carbon::now());
    [$wrestlerA, $wrestlerB] = Wrestler::factory()
        ->has(Employment::factory()->started($startDate->copy()->subWeek()))
        ->count(2)
        ->create();

    $data = StoreRequest::factory()->create([
        'start_date' => $startDate->toDateTimeString(),
        'wrestlerA' => $wrestlerA->getKey(),
        'wrestlerB' => $wrestlerB->getKey(),
    ]);

    $this->actingAs(administrator())
        ->from(action([TagTeamsController::class, 'create']))
        ->post(action([TagTeamsController::class, 'store']), $data);

    expect(TagTeam::latest()->first())
        ->employments->toHaveCount(1)
        ->wrestlers->each(function ($wrestler) use ($startDate) {
            $wrestler->employments->toHaveCount(1)
                ->pivot->joined_at->toEqual($startDate->toDateTimeString());
        });
});

test('unemployed wrestlers are employed on the same date if start date is filled in request', function () {
    testTime()->freeze($startDate = Carbon::now());
    [$wrestlerA, $wrestlerB] = Wrestler::factory()
        ->unemployed()
        ->count(2)
        ->create();

    $data = StoreRequest::factory()->create([
        'start_date' => $startDate->toDateTimeString(),
        'wrestlerA' => $wrestlerA->getKey(),
        'wrestlerB' => $wrestlerB->getKey(),
    ]);

    $this->actingAs(administrator())
        ->from(action([TagTeamsController::class, 'create']))
        ->post(action([TagTeamsController::class, 'store']), $data);

    expect(TagTeam::latest()->first())
        ->employments->toHaveCount(1)
        ->wrestlers->each(function ($wrestler) use ($startDate) {
            $wrestler->employments->toHaveCount(1)
                ->pivot->joined_at->toEqual($startDate->toDateTimeString());
        });
});

test('unemployed wrestlers are joined at the current date if start date is not filled in request', function () {
    testTime()->freeze($startDate = Carbon::now());
    [$wrestlerA, $wrestlerB] = Wrestler::factory()
        ->unemployed()
        ->count(2)
        ->create();

    $data = StoreRequest::factory()->create([
        'wrestlerA' => $wrestlerA->getKey(),
        'wrestlerB' => $wrestlerB->getKey(),
    ]);

    $this->actingAs(administrator())
        ->from(action([TagTeamsController::class, 'create']))
        ->post(action([TagTeamsController::class, 'store']), $data);

    expect(TagTeam::latest()->first())
        ->employments->toBeEmpty()
        ->wrestlers->each(function ($wrestler) use ($startDate) {
            $wrestler->pivot->joined_at->toEqual($startDate->toDateTimeString());
        });
});

test('a basic user cannot create a tag team', function () {
    $data = StoreRequest::factory()->create();

    $this->actingAs(basicUser())
        ->post(action([TagTeamsController::class, 'store']), $data)
        ->assertForbidden();
});

test('a guest cannot create a tag team', function () {
    $data = StoreRequest::factory()->create();

    $this->post(action([TagTeamsController::class, 'store']), $data)
        ->assertRedirect(route('login'));
});
