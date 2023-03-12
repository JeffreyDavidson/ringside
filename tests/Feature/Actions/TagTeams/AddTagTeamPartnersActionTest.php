<?php

use App\Actions\TagTeams\AddTagTeamPartnersAction;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use function Pest\Laravel\mock;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertTrue;
use function Spatie\PestPluginTestTime\testTime;

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
            assertTrue($tagTeamToHaveWrestler->is($tagTeam));
            assertCount(2, $wrestlersToJoinTagTeam);
            assertTrue($wrestlersToJoinTagTeam->contains($wrestlers[0]));
            assertTrue($wrestlersToJoinTagTeam->contains($wrestlers[1]));
            assertTrue($joinDate->equalTo($datetime));

            return true;
        });

    AddTagTeamPartnersAction::run($tagTeam, $wrestlers);
});
