<?php

namespace App\Http\Controllers\Wrestlers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wrestlers\StoreRequest;
use App\Http\Requests\Wrestlers\UpdateRequest;
use App\Models\Wrestler;
use App\Services\WrestlerService;

class WrestlersController extends Controller
{
    /**
     * View a list of employed wrestlers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewList', Wrestler::class);

        return view('wrestlers.index');
    }

    /**
     * Show the form for creating a new wrestler.
     *
     * @return \Illuminate\View\View
     */
    public function create(Wrestler $wrestler)
    {
        $this->authorize('create', Wrestler::class);

        return view('wrestlers.create', compact('wrestler'));
    }

    /**
     * Create a new wrestler.
     *
     * @param  \App\Http\Requests\Wrestlers\StoreRequest  $request
     * @param  \App\Services\WrestlerService  $wrestlerService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request, WrestlerService $wrestlerService)
    {
        $wrestlerService->create($request->only('name', 'height', 'weight', 'hometown', 'signature_move', 'started_at'));

        return redirect()->route('wrestlers.index');
    }

    /**
     * Show the profile of a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\View\View
     */
    public function show(Wrestler $wrestler)
    {
        $this->authorize('view', $wrestler);

        return view('wrestlers.show', compact('wrestler'));
    }

    /**
     * Show the form for editing a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\View\View
     */
    public function edit(Wrestler $wrestler)
    {
        $this->authorize('update', $wrestler);

        return view('wrestlers.edit', compact('wrestler'));
    }

    /**
     * Update a given wrestler.
     *
     * @param  \App\Http\Requests\Wrestlers\UpdateRequest  $request
     * @param  \App\Models\Wrestler  $wrestler
     * @param  \App\Services\WrestlerService  $wrestlerService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Wrestler $wrestler, WrestlerService $wrestlerService)
    {
        $wrestlerService->update($wrestler, $request->only('name', 'height', 'weight', 'hometown', 'signature_move', 'started_at'));

        return redirect()->route('wrestlers.index');
    }

    /**
     * Delete a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Wrestler $wrestler)
    {
        $this->authorize('delete', $wrestler);

        $wrestler->delete();

        return redirect()->route('wrestlers.index');
    }
}
