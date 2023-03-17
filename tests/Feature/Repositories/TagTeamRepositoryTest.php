<?php

use App\Data\TagTeamData;
use App\Models\Employment;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;

test('creates a tag team without a signature move', function () {
    $data = new TagTeamData('Example Tag Team Name', null, null, null, null);

    $tagTeam = app(TagTeamRepository::class)->create($data);

    expect($tagTeam)
        ->name->toEqual('Example Tag Team Name')
        ->signature_move->toBeNull();
});

test('creates a tag team with a signature move', function () {
    $data = new TagTeamData('Example TagTeam Name', 'Tag Team Powerbomb', null, null, null);

    $tagTeam = app(TagTeamRepository::class)->create($data);

    expect($tagTeam)
        ->name->toEqual('Example TagTeam Name')
        ->signature_move->toEqual('Tag Team Powerbomb');
});

test('updates a tag team without a signature move', function () {
    $tagTeam = TagTeam::factory()->create();
    $data = new TagTeamData('Example Tag Team Name', null, null, null, null);

    $tagTeam = app(TagTeamRepository::class)->update($tagTeam, $data);

    expect($tagTeam)
        ->name->toEqual('Example Tag Team Name')
        ->signature_move->toBeNull();
});

test('updates a tag team with a signature move', function () {
    $tagTeam = TagTeam::factory()->create();
    $data = new TagTeamData('Example Tag Team Name', 'Tag Team Powerbomb', null, null, null);

    $tagTeam = app(TagTeamRepository::class)->update($tagTeam, $data);

    expect($tagTeam)
        ->name->toEqual('Example Tag Team Name')
        ->signature_move->toEqual('Tag Team Powerbomb');
});

test('deletes a tag team', function () {
    $tagTeam = TagTeam::factory()->create();

    app(TagTeamRepository::class)->delete($tagTeam);

    expect($tagTeam->fresh())
        ->deleted_at->not->toBeNull();
});

test('restores a tag team', function () {
    $tagTeam = TagTeam::factory()->trashed()->create();

    app(TagTeamRepository::class)->restore($tagTeam);

    expect($tagTeam->fresh())
        ->deleted_at->toBeNull();
});

test('it can employ a tag team', function () {
    $wrestlers = Wrestler::factory()->count(2)->create();
    $tagTeam = TagTeam::factory()->withCurrentWrestlers($wrestlers)->create();
    $datetime = now();

    $tagTeam = app(TagTeamRepository::class)->employ($tagTeam, $datetime);

    expect($tagTeam->fresh())->employments->toHaveCount(1);
    expect($tagTeam->fresh()->employments->first())->started_at->equalTo($datetime);
});

test('it can update an employment of a tag team', function () {
    $datetime = now();
    $tagTeam = TagTeam::factory()
        ->has(Employment::factory()->started($datetime->copy()->addDays(2)))
        ->create();

    expect($tagTeam->fresh())->employments->toHaveCount(1);
    expect($tagTeam->fresh()->employments->first())
        ->started_at->equalTo($datetime->copy()->addDays(2));

    $tagTeam = app(TagTeamRepository::class)->employ($tagTeam, $datetime);

    expect($tagTeam->fresh())->employments->toHaveCount(1);
    expect($tagTeam->fresh()->employments->first())
        ->started_at->equalTo($datetime);
});

test('it can release a tag team', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $datetime = now();

    $tagTeam = app(TagTeamRepository::class)->release($tagTeam, $datetime);

    expect($tagTeam->fresh())->employments->toHaveCount(1);
    expect($tagTeam->fresh()->employments->first())
        ->ended_at->equalTo($datetime);
});

test('it can retire a tag team', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $datetime = now();

    $tagTeam = app(TagTeamRepository::class)->retire($tagTeam, $datetime);

    expect($tagTeam->fresh())->retirements->toHaveCount(1);
    expect($tagTeam->fresh()->retirements->first())
        ->started_at->equalTo($datetime);
});

test('it can unretire a tag team', function () {
    $wrestlers = Wrestler::factory()->count(2)->create();
    $tagTeam = TagTeam::factory()->withPreviousWrestlers($wrestlers)->retired()->create();
    $datetime = now();

    $tagTeam = app(TagTeamRepository::class)->unretire($tagTeam, $datetime);

    expect($tagTeam->fresh())->retirements->toHaveCount(1);
    expect($tagTeam->fresh()->retirements->first())
        ->ended_at->equalTo($datetime);
});

test('it can suspend a tag team', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $datetime = now();

    $tagTeam = app(TagTeamRepository::class)->suspend($tagTeam, $datetime);

    expect($tagTeam->fresh())->suspensions->toHaveCount(1);
    expect($tagTeam->fresh()->suspensions->first())
        ->started_at->equalTo($datetime);
});

test('it can reinstate a tag team', function () {
    $tagTeam = TagTeam::factory()->suspended()->create();
    $datetime = now();

    $tagTeam = app(TagTeamRepository::class)->reinstate($tagTeam, $datetime);

    expect($tagTeam->fresh())->suspensions->toHaveCount(1);
    expect($tagTeam->fresh()->suspensions->first())
        ->ended_at->equalTo($datetime);
});

test('it can update a future employment for a tag team', function () {
    $tagTeam = TagTeam::factory()->hasFutureEmployment()->create();
    $datetime = now();

    $tagTeam = app(TagTeamRepository::class)->updateEmployment($tagTeam, $datetime);

    expect($tagTeam->fresh())->employments->toHaveCount(1);
    expect($tagTeam->fresh()->employments->first())
        ->started_at->equalTo($datetime);
});

test('it can add wrestlers to a tag team', function () {
    $wrestlers = Wrestler::factory()->count(2)->create();
    $tagTeam = TagTeam::factory()->create();
    $datetime = now();

    app(TagTeamRepository::class)->addTagTeamPartners($tagTeam, $wrestlers, $datetime);

    expect($tagTeam->fresh())->wrestlers->toHaveCount(2);
    expect($tagTeam->fresh())->wrestlers->each->pivot->left_at->toBeNull();
});

test('it can add a wrestler to a tag team', function () {
    $wrestler = Wrestler::factory()->create();
    $tagTeam = TagTeam::factory()->create();
    $datetime = now();

    app(TagTeamRepository::class)->addTagTeamPartner($tagTeam, $wrestler, $datetime);

    expect($tagTeam->fresh())->wrestlers->toHaveCount(1);
    expect($tagTeam->fresh())->wrestlers->where('id', $wrestler->id)->first()->pivot->left_at->toBeNull();
});

test('it can remove wrestlers from a tag team', function () {
    $wrestlers = Wrestler::factory()->count(2)->create();
    $tagTeam = TagTeam::factory()->withCurrentWrestlers($wrestlers)->create();
    $datetime = now();

    app(TagTeamRepository::class)->removeTagTeamPartners($tagTeam, $wrestlers, $datetime);

    expect($tagTeam->fresh())->wrestlers->toHaveCount(2);
    expect($tagTeam->fresh())->wrestlers->each->dd()->pivot->dd()->left_at->not->toBeNull();
});

test('it can remove a wrestler from a tag team', function () {
    $wrestler = Wrestler::factory()->create();
    $tagTeam = TagTeam::factory()->withCurrentWrestler($wrestler)->create();
    $datetime = now();

    app(TagTeamRepository::class)->removeTagTeamPartner($tagTeam, $wrestler, $datetime);

    expect($tagTeam->fresh())->previousWrestlers->toHaveCount(1);
    expect($tagTeam->fresh())->previousWrestlers->where('id', $wrestler->id)->first()->pivot->left_at->not->toBeNull();
});

test('it can sync wrestlers from a tag team', function () {
    $currentTagTeamPartners = Wrestler::factory()->count(2)->create();
    $newTagTeamPartners = Wrestler::factory()->count(2)->create();
    $tagTeam = TagTeam::factory()->withCurrentWrestlers($currentTagTeamPartners)->create();

    app(TagTeamRepository::class)->syncTagTeamPartners($tagTeam, $currentTagTeamPartners, $newTagTeamPartners);

    expect($tagTeam->fresh())->wrestlers->toHaveCount(4);
    expect($tagTeam->fresh())->previousWrestlers->toHaveCount(2);
    expect($tagTeam->fresh()->previousWrestlers->pluck('id')->toArray())->toMatchArray($currentTagTeamPartners->pluck('id')->toArray());
    expect($tagTeam->fresh())->currentWrestlers->toHaveCount(2);
    expect($tagTeam->fresh()->currentWrestlers->pluck('id')->toArray())->toMatchArray($newTagTeamPartners->pluck('id')->toArray());
});
