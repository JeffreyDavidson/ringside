<?php

use App\Actions\TagTeams\AddTagTeamPartnersAction;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

beforeEach(function () {
    testTime()->freeze();

    $this->tagTeamRepository = mock(TagTeamRepository::class);
});

test('it can add wrestler to a tag team', function () {
    $wrestlers = Wrestler::factory()->count(2)->create();
    $tagTeam = TagTeam::factory()->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('addTagTeamPartners')
        ->once()
        ->withArgs(function (TagTeam $tagTeamToHaveWrestler, Collection $wrestlersToJoinTagTeam, Carbon $joinDate) use ($tagTeam, $wrestlers, $datetime) {
            expect($tagTeamToHaveWrestler->is($tagTeam))->toBeTrue();
            expect($wrestlersToJoinTagTeam)->toHaveCount(2);
            expect($wrestlersToJoinTagTeam->contains($wrestlers[0]))->toBeTrue();
            expect($wrestlersToJoinTagTeam->contains($wrestlers[1]))->toBeTrue();
            expect($joinDate->equalTo($datetime))->toBeTrue();

            return true;
        });

    AddTagTeamPartnersAction::run($tagTeam, $wrestlers);
});
