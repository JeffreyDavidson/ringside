<?php

namespace App\Http\Controllers;

use App\Wrestler;

class InactiveWrestlersController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function index() {

    }

    /**
     * Deactivate an inactive wrestler.
     *
     * @param  \App\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function store(Wrestler $wrestler)
    {
        $this->authorize('deactivate', Wrestler::class);

        abort_unless($wrestler->isActive(), 403);

        $wrestler->deactivate();

        return redirect()->route('inactive-wrestlers.index');
    }
}
