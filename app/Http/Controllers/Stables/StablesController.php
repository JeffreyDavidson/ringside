<?php

namespace App\Http\Controllers\Stables;

use App\Models\Stable;
use Illuminate\Http\Request;
use App\Filters\StableFilters;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStableRequest;
use App\Http\Requests\UpdateStableRequest;

class StablesController extends Controller
{
    /**
     * View a list of stables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Yajra\DataTables\DataTables  $table
     * @return \Illuminate\View\View
     */
    public function index(Request $request, DataTables $table, StableFilters $requestFilter)
    {
        $this->authorize('viewList', Stable::class);

        if ($request->ajax()) {
            $query = Stable::query();
            $requestFilter->apply($query);

            return $table->eloquent($query)
                ->addColumn('action', 'stables.partials.action-cell')
                ->editColumn('started_at', function (Stable $stable) {
                    return $stable->employment->started_at ?? null;
                })
                ->filterColumn('id', function ($query, $keyword) {
                    $query->where($query->qualifyColumn('id'), $keyword);
                })
                ->toJson();
        }

        return view('stables.index');
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

    /**
     * Show the profile of a tag team.
     *
     * @param  \App\Models\Stable  $stable
     * @return \Illuminate\Http\Response
     */
    public function show(Stable $stable)
    {
        $this->authorize('view', $stable);

        return response()->view('stables.show', compact('stable'));
    }

    /**
     * Show the form for editing a stable.
     *
     * @param  \App\Models\Stable  $stable
     * @return \lluminate\Http\Response
     */
    public function edit(Stable $stable)
    {
        $this->authorize('update', Stable::class);

        return response()->view('stables.edit', compact('stable'));
    }

    /**
     * Update a given stable.
     *
     * @param  \App\Http\Requests\UpdateStableRequest  $request
     * @param  \App\Models\Stable  $stable
     * @return \lluminate\Http\RedirectResponse
     */
    public function update(UpdateStableRequest $request, Stable $stable)
    {
        $stable->update($request->except('wrestlers', 'tagteams'));

        $newStableWrestlers = $request->input('wrestlers');
        $newStableTagTeams = $request->input('tagteams');

        $currentStableWrestlers = $stable->wrestlers()->whereNull('left_at')->get()->pluck('id');
        $currentStableTagTeams = $stable->tagteams()->whereNull('left_at')->get()->pluck('id');

        $formerStableWrestlers = $currentStableWrestlers->diff(collect($newStableWrestlers));
        $formerStableTagTeams = $currentStableTagTeams->diff(collect($newStableTagTeams));

        $stable->wrestlers()->updateExistingPivot($formerStableWrestlers, ['left_at' => now()]);
        $stable->tagteams()->updateExistingPivot($formerStableTagTeams, ['left_at' => now()]);

        $stable->wrestlers()->syncWithoutDetaching($newStableWrestlers);
        $stable->tagteams()->syncWithoutDetaching($newStableTagTeams);

        return redirect()->route('stables.index');
    }

    /**
     * Delete a stable.
     *
     * @param  App\Models\Stable  $stable
     * @return \lluminate\Http\RedirectResponse
     */
    public function destroy(Stable $stable)
    {
        $this->authorize('delete', Stable::class);

        $stable->delete();

        return redirect()->route('stables.index');
    }

    /**
     * Restore a deleted stable.
     *
     * @param  int  $stableId
     * @return \lluminate\Http\RedirectResponse
     */
    public function restore($stableId)
    {
        $stable = Stable::onlyTrashed()->findOrFail($stableId);

        $this->authorize('restore', Stable::class);

        $stable->restore();

        return redirect()->route('stables.index');
    }
}
