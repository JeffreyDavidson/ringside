<?php

namespace App\Http\Controllers;

use App\Wrestler;
use App\Retirement;
use App\Http\Requests\StoreRetirementRequest;

class RetirementsController extends Controller
{
    /**
     * Create a retirement for the wrestler.
     *
     * @param  \App\Http\Requests\StoreRetirementRequest  $request
     * @param  \App\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function store(StoreRetirementRequest $request, Wrestler $wrestler)
    {
        $wrestler->retire();

        return redirect(route('retired-wrestlers.index'));
    }

    /**
     * Unretire a retired wrestler.
     *
     * @param  \App\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function destroy(Wrestler $wrestler)
    {
        $this->authorize('unretire', $wrestler);

        $wrestler->unretire();

        return redirect(route('wrestler.index'));
    }
}
