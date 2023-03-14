<?php

use App\Actions\TagTeams\AddTagTeamPartnerAction;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;
use Illuminate\Support\Carbon;

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
            expect($tagTeamToHaveWrestler->is($tagTeam))->toBeTrue();
            expect($wrstlerToJoinTagTeam->is($wrestler))->toBeTrue();
            expect($joinDate->equalTo($datetime))->toBeTrue();

            return true;
        });

    AddTagTeamPartnerAction::run($tagTeam, $wrestler);
});
