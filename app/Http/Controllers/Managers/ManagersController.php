<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ViewModels\ManagerViewModel;
use App\DataTables\ManagersDataTable;
use App\Http\Requests\Managers\StoreRequest;
use App\Http\Requests\Managers\UpdateRequest;

class ManagersController extends Controller
{
    /**
     * View a list of managers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\DataTables\ManagersDataTable  $dataTable
     * @return \Illuminate\View\View
     */
    public function index(Request $request, ManagersDataTable $dataTable)
    {
        $this->authorize('viewList', Manager::class);

        if ($request->ajax()) {
            return $dataTable->ajax();
        }

        return view('managers.index');
    }

    /**
     * Show the form for creating a manager.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Manager $manager)
    {
        $this->authorize('create', Manager::class);

        return view('managers.create', new ManagerViewModel());
    }

    /**
     * Create a new manager.
     *
     * @param  \App\Http\Requests\Managers\StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $manager = Manager::create($request->except('started_at'));

        if ($request->filled('started_at')) {
            $manager->employ($request->input('started_at'));
        }

        return redirect()->route('managers.index');
    }

    /**
     * Show the profile of a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function show(Manager $manager)
    {
        $this->authorize('view', $manager);

        return view('managers.show', compact('manager'));
    }

    /**
     * Show the form for editing a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function edit(Manager $manager)
    {
        $this->authorize('update', $manager);

        return view('managers.edit', new ManagerViewModel($manager));
    }

    /**
     * Update a given manager.
     *
     * @param  \App\Http\Requests\Managers\UpdateRequest  $request
     * @param  \App\Models\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Manager $manager)
    {
        $manager->update($request->except('started_at'));

        if ($request->filled('started_at')) {
            $manager->employ($request->input('started_at'));
        }

        return redirect()->route('managers.index');
    }

    /**
     * Delete a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function destroy(Manager $manager)
    {
        $this->authorize('delete', $manager);

        $manager->delete();

        return redirect()->route('managers.index');
    }
}
