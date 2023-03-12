<?php

use App\Actions\TagTeams\RemoveTagTeamPartnerAction;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use Illuminate\Support\Carbon;
use function Pest\Laravel\mock;
use function PHPUnit\Framework\assertTrue;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    testTime()->freeze();

    $this->tagTeamRepository = mock(TagTeamRepository::class);
});

test('it can remove a wrestler from a tag team at the current datetime by default', function () {
    $wrestler = Wrestler::factory()->create();
    $tagTeam = TagTeam::factory()->withCurrentWrestler($wrestler)->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('removeTagTeamPartner')
        ->once()
        ->withArgs(function (TagTeam $tagTeamToRemoveWrestler, Wrestler $removedWrestler, Carbon $removalDate) use ($tagTeam, $wrestler, $datetime) {
            assertTrue($tagTeamToRemoveWrestler->is($tagTeam));
            assertTrue($removedWrestler->is($wrestler));
            assertTrue($removalDate->equalTo($datetime));

            return true;
        });

    RemoveTagTeamPartnerAction::run($tagTeam, $wrestler);
});

test('it can remove a wrestler from a tag team at a specific datetime', function () {
    $wrestler = Wrestler::factory()->create();
    $tagTeam = TagTeam::factory()->withCurrentWrestler($wrestler)->create();
    $datetime = now()->addDays(3);

    $this->tagTeamRepository
        ->shouldReceive('removeTagTeamPartner')
        ->once()
        ->with($tagTeam, $wrestler, $datetime);

    RemoveTagTeamPartnerAction::run($tagTeam, $wrestler, $datetime);
});
