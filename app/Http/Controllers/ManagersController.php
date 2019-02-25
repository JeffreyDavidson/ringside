<?php

namespace App\Http\Controllers;

use App\Manager;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;

class ManagersController extends Controller
{
    /**
     * Show the form for creating a manager.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Manager::class);

        return view('managers.create');
    }

    /**
     * Create a new manager.
     *
     * @param  \App\Http\Requests\StoreManagerRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreManagerRequest $request)
    {
        Manager::create($request->all());

        return redirect()->route('managers.index');
    }

    /**
     * Show the form for editing a manager.
     *
     * @param  \App\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function edit(Manager $manager)
    {
        $this->authorize('update', Manager::class);

        return view('managers.edit', compact('manager'));
    }

    /**
     * Update a given wrestler.
     *
     * @param  \App\Http\Requests\UpdateManagerRequest  $request
     * @param  \App\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function update(UpdateManagerRequest $request, Manager $manager)
    {
        $manager->update($request->all());

        return redirect()->route('managers.index');
    }

    /**
     * Delete a manager.
     *
     * @param  App\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function destroy(Manager $manager)
    {
        $this->authorize('delete', Manager::class);

        $manager->delete();

        return redirect()->route('managers.index');
    }

    /**
     * Restore a deleted wrestler.
     *
     * @param  int  $wrestlerId
     * @return \lluminate\Http\RedirectResponse
     */
    public function restore($managerId)
    {
        $manager = Manager::onlyTrashed()->findOrFail($managerId);

        $this->authorize('restore', Manager::class);

        $manager->restore();

        return redirect()->route('managers.index');
    }
}
