<?php

namespace App\Http\Controllers;

use App\Wrestler;
use App\Http\Requests\StoreWrestlerRequest;
use App\Http\Requests\UpdateWrestlerRequest;

class WrestlersController extends Controller
{
    /**
     * Show the form for creating a new wrestler.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Wrestler::class);

        return response()->view('wrestlers.create');
    }

    /**
     * Create a new wrestler.
     *
     * @param  \App\Http\Requests\StoreWrestlerRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreWrestlerRequest $request)
    {
        $request->merge(['height' => ($request->input('feet') * 12) + $request->input('inches')]);

        $wrestler = Wrestler::create($request->except(['feet', 'inches']));

        return redirect(route('wrestler.index'));
    }

    /**
     * Edit a wrestler.
     *
     * @param  \App\Wrestler  $wrestler
     * @return \lluminate\Http\Response
     */
    public function edit(Wrestler $wrestler)
    {
        $this->authorize('update', $wrestler);

        return response()->view('wrestlers.edit', compact('wrestler'));
    }

    /**
     * Update a given wrestler.
     *
     * @param  \App\Http\Requests\UpdateWrestlerRequest  $request
     * @param  \App\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function update(UpdateWrestlerRequest $request, Wrestler $wrestler)
    {
        $request->merge(['height' => ($request->input('feet') * 12) + $request->input('inches')]);

        $wrestler->update($request->except(['feet', 'inches']));

        return redirect(route('wrestler.index'));
    }

    /**
     * Undocumented function
     *
     * @param  App\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function destroy(Wrestler $wrestler)
    {
        $this->authorize('delete', $wrestler);

        $wrestler->delete();

        return redirect(route('wrestler.index'));
    }
}
