<?php

use App\Actions\TagTeams\AddTagTeamPartnerAction;
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

test('it can add a wrestler to a tag team', function () {
    $wrestler = Wrestler::factory()->create();
    $tagTeam = TagTeam::factory()->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('addTagTeamPartner')
        ->once()
        ->withArgs(function (TagTeam $tagTeamToHaveWrestler, Wrestler $wrstlerToJoinTagTeam, Carbon $joinDate) use ($tagTeam, $wrestler, $datetime) {
            assertTrue($tagTeamToHaveWrestler->is($tagTeam));
            assertTrue($wrstlerToJoinTagTeam->is($wrestler));
            assertTrue($joinDate->equalTo($datetime));

            return true;
        });

    AddTagTeamPartnerAction::run($tagTeam, $wrestler);
});
