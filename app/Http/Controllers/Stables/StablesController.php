<?php

namespace App\Http\Controllers\Stables;

use App\Models\Stable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStableRequest;

class StablesController extends Controller
{
    /**
     * Retrieve stables of a specific state.
     *
     * @param  string  $state
     * @return \Illuminate\Http\Response
     */
    public function index($state = 'active')
    {
        $this->authorize('viewList', Stable::class);

        $stables = Stable::hasState($state)->get();

        return response()->view('stables.index', compact('stables'));
    }

    /**
     * Show the form for creating a stable.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Stable::class);

        return view('stables.create');
    }

    /**
     * Create a new stable.
     *
     * @param  \App\Http\Requests\StoreStableRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreStableRequest $request)
    {
        $stable = Stable::create($request->except(['wrestlers', 'tagteams']));

        $stable->addWrestlers($request->only('wrestlers'))->addTagTeams($request->only('tagteams'));

        return redirect()->route('stables.index');
    }
}
