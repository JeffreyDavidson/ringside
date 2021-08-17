<?php

namespace App\Http\Controllers\Stables;

use App\Exceptions\CannotBeDisbandedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stables\DisbandRequest;
use App\Models\Stable;
use App\Repositories\StableRepository;

class DisbandController extends Controller
{
    /**
     * Disband a stable.
     *
     * @param  \App\Models\Stable  $stable
     * @param  \App\Http\Requests\Stables\DisbandRequest  $request
     * @param  \App\Repositories\StableRepository  $stableRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Stable $stable, DisbandRequest $request, StableRepository $stableRepository)
    {
        throw_unless($stable->canBeDisbanded(), new CannotBeDisbandedException);

        $disbandedDate = now()->toDateTimeString();

        $stable->currentActivation()->update(['ended_at' => $disbandedDate]);
        $stable->currentWrestlers()->detach();
        $stable->currentTagTeams()->detach();
        $stable->updateStatusAndSave();

        return redirect()->route('stables.index');
    }
}
