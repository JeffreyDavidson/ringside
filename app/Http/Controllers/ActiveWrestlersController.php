<?php

namespace App\Http\Controllers;

use App\Wrestler;

class ActiveWrestlersController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        $this->authorize('view-active-wrestlers', Wrestler::class);

        $activeWrestlers = Wrestler::active()->get();

        return response()->view('wrestlers.index', ['wrestlers' => $activeWrestlers]);
    }

    /**
     * Activate and inactive wrestler.
     *
     * @param  \App\Wrestler $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function store(Wrestler $wrestler)
    {
        $this->authorize('activate', Wrestler::class);

        abort_if($wrestler->isActive(), 403);

        $wrestler->activate();

        return redirect()->route('active-wrestlers.index');
    }
}
