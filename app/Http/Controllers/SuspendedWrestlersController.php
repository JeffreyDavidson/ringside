<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSuspensionRequest;

class SuspendedWrestlersController extends Controller
{
    /**
     * Create a suspension for the wrestler.
     *
     * @param  \App\Http\Requests\StoreSuspensionRequest  $request
     * @param  \App\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function store(StoreSuspensionRequest $request, Wrestler $wrestler)
    {
        $wrestler->suspend();

        return redirect(route('suspended-wrestlers.index'));
    }

    /**
     * Reinstate a suspended wrestler.
     *
     * @param  \App\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function destroy(Wrestler $wrestler)
    {
        $this->authorize('reinstate', $wrestler);

        $wrestler->reinstate();

        return redirect(route('wrestler.index'));
    }
}
