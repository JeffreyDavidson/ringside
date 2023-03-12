<?php

use App\Http\Requests\TagTeams\UpdateRequest;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Rules\EmploymentStartDateCanBeChanged;
use App\Rules\LetterSpace;
use function Pest\Laravel\mock;
use Illuminate\Support\Carbon;
use Tests\RequestFactories\TagTeamRequestFactory;

test('an administrator is authorized to make this request', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->by(administrator())
        ->assertAuthorized();
});

test('a non administrator is not authorized to make this request', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->by(basicUser())
        ->assertNotAuthorized();
});

test('tag team name is required', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'name' => null,
        ]))
        ->assertFailsValidation(['name' => 'required']);
});

test('tag team name must be a string', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'name' => 123,
        ]))
        ->assertFailsValidation(['name' => 'string']);
});

test('tag team name must be a contain only letters and spaces', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'name' => 'ac!',
        ]))
        ->assertFailsValidation(['name' => LetterSpace::class]);
});

test('tag team name must be at least 3 characters', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'name' => 'ab',
        ]))
        ->assertFailsValidation(['name' => 'min:3']);
});

test('tag team name must be unique', function () {
    $tagTeamA = TagTeam::factory()->create(['name' => 'Example Tag Team Name A']);
    TagTeam::factory()->create(['name' => 'Example Tag Team Name B']);

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeamA)
        ->validate(TagTeamRequestFactory::new()->create([
            'name' => 'Example Tag Team Name B',
        ]))
        ->assertFailsValidation(['name' => 'unique:tag_teams,NULL,'.$tagTeamA->id.',id']);
});

test('tag team signature move is optional if wrestlers are not provided', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'signature_move' => null,
        ]))
        ->assertPassesValidation();
});

test('tag team signature move must be a string if provided', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'signature_move' => 12345,
        ]))
        ->assertFailsValidation(['signature_move' => 'string']);
});

test('tag team signature move can only contain valid characters if provided', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'signature_move' => 'dfaf afd!$%^',
        ]))
        ->assertFailsValidation(['signature_move' => 'regex:/^[a-zA-Z\s\']+$/']);
});

test('tag team start date is optional', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'start_date' => null,
        ]))
        ->assertPassesValidation();
});

test('tag team start date must be a string if provided', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'start_date' => 12345,
        ]))
        ->assertFailsValidation(['start_date' => 'string']);
});

test('tag team start date must be in the correct date format', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'start_date' => 'not-a-date-format',
        ]))
        ->assertFailsValidation(['start_date' => 'date']);
});

test('tag team start date cannot be changed if employment start date has past', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    mock(EmploymentStartDateCanBeChanged::class)
        ->shouldReceive('validate')
        ->with('wrestlerA', 1, function ($closure) {
            $closure();
            return true;
        });

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'start_date' => Carbon::now()->toDateTImeString(),
        ]))
        ->assertFailsValidation(['start_date' => EmploymentStartDateCanBeChanged::class]);
})->skip();

test('tag team wrestlerA is optional', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => null,
        ]))
        ->assertPassesValidation();
});

test('tag team wrestlerA must be an integer if provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => 'not-an-integer',
        ]))
        ->assertFailsValidation(['wrestlerA' => 'integer']);
});

test('tag team wrestlerA must be different from wrestlerB if provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestlerA = Wrestler::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => $wrestlerA->id,
            'wrestlerB' => $wrestlerA->id
        ]))
        ->assertFailsValidation(['wrestlerA' => 'different:wrestlerB']);
});

test('tag team wrestlerA is required if start date is provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => null,
            'start_date' => Carbon::now()->toDateTimeString()
        ]))
        ->assertFailsValidation(['wrestlerA' => 'required_with:start_date']);
});

test('tag team wrestlerA is required with wrestlerB is provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = Wrestler::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => null,
            'wrestlerB' => $wrestler->id
        ]))
        ->assertFailsValidation(['wrestlerA' => 'required_with:wrestlerB']);
});

test('tag team wrestlerA must exist if provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => 999999,
        ]))
        ->assertFailsValidation(['wrestlerA' => 'exists:wrestlers,id']);
});

test('tag team wrestlerA must be able to join an existing tag team if provided', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->bookable()->count(2)->create();
    $wrestlerC = Wrestler::factory()->bookable()->create();
    $tagTeam = TagTeam::factory()->bookable()->withCurrentWrestlers([$wrestlerA, $wrestlerB])->create();

    mock(WrestlerCanJoinExistingTagTeam::class)
        ->shouldReceive('validate')
        ->with('wrestlerA', 1, function ($closure) {
            $closure();
            return true;
        });

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => $wrestlerC->id,
            'wrestlerB' => $wrestlerB->id
        ]))
        ->assertFailsValidation(['wrestlerA' => WrestlerCanJoinExistingTagTeam::class]);
});

test('tag team wrestlerB is optional', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => null,
        ]))
        ->assertPassesValidation();
});

test('tag team wrestlerB must be an integer if provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => 'not-an-integer',
        ]))
        ->assertFailsValidation(['wrestlerB' => 'integer']);
});

test('tag team wrestlerB must be different from wrestlerA if provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestlerB = Wrestler::factory()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => $wrestlerB->id,
            'wrestlerB' => $wrestlerB->id
        ]))
        ->assertFailsValidation(['wrestlerB' => 'different:wrestlerA']);
});

test('tag team wrestlerB is required if start date is provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => null,
            'start_date' => Carbon::now()->toDateTimeString()
        ]))
        ->assertFailsValidation(['wrestlerB' => 'required_with:start_date']);
});

test('tag team wrestlerB is required with wrestlerA is provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = Wrestler::factory()->create();

    dd($this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => $wrestler->id,
            'wrestlerB' => null,
        ]))->getFailedRules());
        // ->assertFailsValidation(['wrestlerB' => 'required_with:wrestlerA']);
});

test('tag team wrestlerB must exist if provided', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerB' => 999999,
        ]))
        ->assertFailsValidation(['wrestlerB' => 'exists:wrestlers,id']);
});

test('tag team wrestlerB must be able to join an existing tag team if provided', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->bookable()->count(2)->create();
    $wrestlerC = Wrestler::factory()->bookable()->create();
    $tagTeam = TagTeam::factory()->bookable()->withCurrentWrestlers([$wrestlerA, $wrestlerB])->create();

    mock(WrestlerCanJoinExistingTagTeam::class)
        ->shouldReceive('validate')
        ->with('wrestlerB', 1, function ($closure) {
            $closure();
            return true;
        });

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'wrestlerA' => $wrestlerC->id,
            'wrestlerB' => $wrestlerA->id
        ]))
        ->assertFailsValidation(['wrestlerB' => WrestlerCanJoinExistingTagTeam::class]);
});
