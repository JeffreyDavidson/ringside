<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use Illuminate\Http\Request;
use App\Filters\ManagerFilters;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;

class ManagersController extends Controller
{
    /**
     * View a list of managers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Yajra\DataTables\DataTables  $table
     * @param  \App\Filters\ManagerFilters  $requestFilter
     * @return \Illuminate\View\View
     */
    public function index(Request $request, DataTables $table, ManagerFilters $requestFilter)
    {
        $this->authorize('viewList', Manager::class);

        if ($request->ajax()) {
            $query = Manager::with('currentEmployment');
            $requestFilter->apply($query);

            return $table->eloquent($query)
                ->addColumn('action', 'managers.partials.action-cell')
                ->filterColumn('name', function ($query, $keyword) {
                    $sql = "CONCAT(managers.first_name, ' ', managers.last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('id', function ($query, $keyword) {
                    $query->where($query->qualifyColumn('id'), $keyword);
                })
                ->toJson();
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

        return view('managers.create', compact('manager'));
    }

    /**
     * Create a new manager.
     *
     * @param  \App\Http\Requests\StoreManagerRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreManagerRequest $request)
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

        return view('managers.edit', compact('manager'));
    }

    /**
     * Update a given manager.
     *
     * @param  \App\Http\Requests\UpdateManagerRequest  $request
     * @param  \App\Models\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function update(UpdateManagerRequest $request, Manager $manager)
    {
        $manager->update($request->except('started_at'));

        $startedAt = $request->input('started_at');
        $isEmployed = $manager->checkIsEmployed();

        if ($startedAt && $isEmployed && Carbon::parse($startedAt)->lt($manager->currentEmployment->started_at)) {
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
