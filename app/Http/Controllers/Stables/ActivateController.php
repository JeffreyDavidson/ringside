<?php

namespace App\Http\Controllers\Stables;

use App\Exceptions\CannotBeActivatedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stables\ActivateRequest;
use App\Models\Stable;
use App\Repositories\StableRepository;

class ActivateController extends Controller
{
    /**
     * Activate a stable.
     *
     * @param  \App\Models\Stable  $stable
     * @param  \App\Http\Requests\Stables\ActivateRequest  $stable
     * @param  \App\Repositories\StableRepository  $stableRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Stable $stable, ActivateRequest $request, StableRepository $stableRepository)
    {
        throw_unless($stable->canBeActivated(), new CannotBeActivatedException);

        $activationDate = now()->toDateTimeString();

        $stableRepository->activate($stable, $activationDate);
        $stable->updateStatusAndSave();

        return redirect()->route('stables.index');
    }
}
