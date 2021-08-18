<?php

namespace App\Http\Controllers\Stables;

use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stables\RetireRequest;
use App\Models\Stable;
use App\Repositories\StableRepository;
use App\Repositories\TagTeamRepository;
use App\Repositories\WrestlerRepository;

class RetireController extends Controller
{
    /**
     * Retire a stable.
     *
     * @param  \App\Models\Stable  $stable
     * @param  \App\Http\Requests\Stables\RetireRequest  $request
     * @param  \App\Repositories\StableRepository  $stableRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Stable $stable, RetireRequest $request, StableRepository $stableRepository)
    {
        throw_unless($stable->canBeRetired(), new CannotBeRetiredException);

        $retirementDate = now()->toDateTimeString();

        $stableRepository->deactivate($stable, $retirementDate);
        $stableRepository->retire($stable, $retirementDate);

        if ($stable->currentTagTeams->isNotEmpty()) {
            $stable->currentTagTeams->each->retire($retirementDate);
        }

        if ($stable->currentWrestlers->isNotEmpty()) {
            $stable->currentWrestlers->each->retire($retirementDate);
        }

        $stable->updateStatusAndSave();

        return redirect()->route('stables.index');
    }
}
