<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInjuryRequest;

class InjuredWrestlersController extends Controller
{
    /**
     * Create an injury for the wrestler.
     *
     * @param  \App\Http\Requests\StoreInjuryRequest  $request
     * @param  \App\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function store(StoreInjuryRequest $request, Wrestler $wrestler)
    {
        $wrestler->injure();

        return redirect(route('injured-wrestlers.index'));
    }

    /**
     * Recover an injured wrestler.
     *
     * @param  \App\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function destroy(Wrestler $wrestler)
    {
        $this->authorize('recover', $wrestler);

        $wrestler->recover();

        return redirect(route('wrestler.index'));
    }
}
