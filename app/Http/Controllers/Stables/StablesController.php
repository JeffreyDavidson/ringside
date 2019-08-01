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
        $stable = Stable::create($request->except(['wrestlers', 'tagteams', 'started_at']));

        if ($request->filled('started_at')) {
            $stable->employments()->create($request->only('started_at'));
        }

        if ($request->filled('wrestlers')) {
            $stable->addWrestlers($request->only('wrestlers'), $request->input('started_at'));
        }

        if ($request->filled('tagteams')) {
            $stable->addTagTeams($request->only('tagteams'), $request->input('started_at'));
        }

        return redirect()->route('roster.stables.index');
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

        return rview('stables.show', compact('stable'));
    }

    /**
     * Show the form for editing a stable.
     *
     * @param  \App\Models\Stable  $stable
     * @return \lluminate\Http\Response
     */
    public function edit(Stable $stable)
    {
        $this->authorize('update', $stable);

        return view('stables.edit', compact('stable'));
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
        $stable->update($request->except('wrestlers', 'tagteams', 'started_at'));

        if ($stable->employment()->exists() && !is_null($request->input('started_at'))) {
            if ($stable->employment->started_at != $request->input('started_at')) {
                $stable->employment()->update($request->only('started_at'));
            }
        } else {
            $stable->employments()->create($request->only('started_at'));
        }

        // We need to get the new list of wrestlers/tag teams that are
        // now current for the stable as set from the form.
        $newStableWrestlers = $request->input('wrestlers');
        $newStableTagTeams = $request->input('tagteams');

        // // We need to get the current wrestlers/tagteams for the stable
        // $currentStableWrestlers = $stable->currentWrestlers()->get()->pluck('wrestlers.id');
        // $currentStableTagTeams = $stable->currentTagTeams()->get()->pluck('tag_teams.id');

        // // We need to find out which wrestlers/tagteams are no longer in the stable
        // $formerStableWrestlers = $currentStableWrestlers->diff(collect($newStableWrestlers));
        // $formerStableTagTeams = $currentStableTagTeams->diff(collect($newStableTagTeams));

        // // We need to update the wrestlers/tagteams no longer in the stable as leaving.
        // $stable->wrestlers()
        //     ->wherePivotIn('id', $formerStableWrestlers)
        //     ->updateExistingPivot($formerStableWrestlers, ['left_at' => now()]);

        // $stable->tagteams()
        //     ->wherePivotIn('id', $formerStableTagTeams)
        //     ->updateExistingPivot($formerStableTagTeams, ['left_at' => now()]);

        // // We need to add the new wrestlers/tagteams added to the stable.
        // // We also need to make sure that the joined_at field is set with the
        // // current datetimestamp (now()).
        // $stable->wrestlers()->syncWithoutDetaching($newStableWrestlers);
        // $stable->tagteams()->syncWithoutDetaching($newStableTagTeams);
        $stable->wrestlers()->sync($request->input('wrestlers'));
        $stable->tagteams()->sync($request->input('tagteams'));


        return redirect()->route('roster.stables.index');
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

        return redirect()->route('roster.stables.index');
    }
}
