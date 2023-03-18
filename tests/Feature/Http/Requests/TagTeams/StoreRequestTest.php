<?php

use App\Http\Requests\TagTeams\StoreRequest;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Rules\WrestlerCanJoinNewTagTeam;
use Illuminate\Support\Carbon;
use function Pest\Laravel\mock;
use Tests\RequestFactories\TagTeamRequestFactory;

test('an administrator is authorized to make this request', function () {
    $this->createRequest(StoreRequest::class)
        ->by(administrator())
        ->assertAuthorized();
});

test('a non administrator is not authorized to make this request', function () {
    $this->createRequest(StoreRequest::class)
        ->by(basicUser())
        ->assertNotAuthorized();
});

test('tag team name is required', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'name' => null,
        ]))
        ->assertFailsValidation(['name' => 'required']);
});

test('tag team name must be a string', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'name' => 123,
        ]))
        ->assertFailsValidation(['name' => 'string']);
});

test('tag team name must be at least 3 characters', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'name' => 'ab',
        ]))
        ->assertFailsValidation(['name' => 'min:3']);
});

test('tag team name must be unique', function () {
    TagTeam::factory()->create(['name' => 'Example Tag Team Name']);

    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'name' => 'Example Tag Team Name',
        ]))
        ->assertFailsValidation(['name' => 'unique:tag_teams,name,NULL,id']);
});

test('tag team signature move is optional', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'signature_move' => null,
        ]))
        ->assertPassesValidation();
});

test('tag team signature move must be a string if provided', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'signature_move' => 12345,
        ]))
        ->assertFailsValidation(['signature_move' => 'string']);
});

test('tag team start date is optional', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'start_date' => null,
        ]))
        ->assertPassesValidation();
});

test('tag team start date must be a string if provided', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'start_date' => 12345,
        ]))
        ->assertFailsValidation(['start_date' => 'string']);
});

test('tag team start date must be in the correct date format', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'start_date' => 'not-a-date',
        ]))
        ->assertFailsValidation(['start_date' => 'date']);
});

test('tag team wrestlerA is optional', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => null,
        ]))
        ->assertPassesValidation();
});

test('tag team wrestlerA must be an integer if provided', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => 'not-an-integer',
        ]))
        ->assertFailsValidation(['wrestlerA' => 'integer']);
});

test('tag team wrestlerA must be different from wrestlerB if provided', function () {
    $wrestlerA = Wrestler::factory()->create();

    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => $wrestlerA->id,
            'wrestlerB' => $wrestlerA->id,
        ]))
        ->assertFailsValidation(['wrestlerA' => 'different:wrestlerB']);
});

test('tag team wrestlerA is required if start date is provided', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => null,
            'start_date' => Carbon::now()->toDateTimeString(),
        ]))
        ->assertFailsValidation(['wrestlerA' => 'required_with:start_date']);
});

test('tag team wrestlerA is required with wrestlerB is provided', function () {
    $wrestler = Wrestler::factory()->create();

    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => null,
            'wrestlerB' => $wrestler->id,
        ]))
        ->assertFailsValidation(['wrestlerA' => 'required_with:wrestlerB']);
});

test('tag team wrestlerA must exist if provided', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => 999999,
        ]))
        ->assertFailsValidation(['wrestlerA' => 'exists:wrestlers,id']);
});

test('tag team wrestlerA must be able to join a new tag team if provided', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->bookable()->count(2)->create();

    mock(WrestlerCanJoinNewTagTeam::class)
        ->shouldReceive('validate')
        ->withArgs('wrestlerA', $wrestlerA->id, function ($closure) {
            dd($closure);
            $closure();
        });

    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => $wrestlerA->id,
            'wrestlerB' => $wrestlerB->id,
        ]))
        ->assertFailsValidation(['wrestlerA' => WrestlerCanJoinNewTagTeam::class]);
});

test('tag team wrestlerB are optional', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => null,
        ]))
        ->assertPassesValidation();
});

test('tag team wrestlerB must be an integer if provided', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => 'not-an-integer',
        ]))
        ->assertFailsValidation(['wrestlerB' => 'integer']);
});

test('tag team wrestlerB must be different from wrestlerA if provided', function () {
    $wrestlerA = Wrestler::factory()->create();

    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => $wrestlerA->id,
            'wrestlerA' => $wrestlerA->id,
        ]))
        ->assertFailsValidation(['wrestlerB' => 'different:wrestlerA']);
});

test('tag team wrestlerB is required if start date is provided', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => null,
            'start_date' => Carbon::now()->toDateTimeString(),
        ]))
        ->assertFailsValidation(['wrestlerB' => 'required_with:start_date']);
});

test('tag team wrestlerB is required with wrestlerA is provided', function () {
    $wrestler = Wrestler::factory()->create();

    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => null,
            'wrestlerA' => $wrestler->id,
        ]))
        ->assertFailsValidation(['wrestlerB' => 'required_with:wrestlerA']);
});

test('tag team wrestlerB is must exist if provided', function () {
    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => 999999,
        ]))
        ->assertFailsValidation(['wrestlerB' => 'exists:wrestlers,id']);
});

test('tag team wrestlerB must be able to join a new tag team if provided', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->bookable()->count(2)->create();

    mock(WrestlerCanJoinNewTagTeam::class)
        ->shouldReceive('validate')
        ->with('wrestlerB', $wrestlerB->id, function ($closure) {
            $closure();
        });

    $this->createRequest(StoreRequest::class)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => $wrestlerA->id,
            'wrestlerB' => $wrestlerB->id,
        ]))
        ->assertFailsValidation(['wrestlerB' => WrestlerCanJoinNewTagTeam::class]);
});
