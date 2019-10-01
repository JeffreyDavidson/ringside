<?php

namespace App\Http\Controllers\TagTeams;

use App\Models\TagTeam;
use Illuminate\Http\Request;
use App\Filters\TagTeamFilters;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagTeamRequest;
use App\Http\Requests\UpdateTagTeamRequest;

class TagTeamsController extends Controller
{
    /**
     * View a list of tag teams.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Yajra\DataTables\DataTables  $table
     * @return \Illuminate\View\View
     */
    public function index(Request $request, DataTables $table, TagTeamFilters $requestFilter)
    {
        $this->authorize('viewList', TagTeam::class);

        if ($request->ajax()) {
            $query = TagTeam::query();
            $requestFilter->apply($query);

            return $table->eloquent($query)
                ->addColumn('action', 'tagteams.partials.action-cell')
                ->editColumn('started_at', function (TagTeam $tagTeam) {
                    return $tagTeam->currentEmployment->started_at->format('Y-m-d H:s');
                })
                ->filterColumn('id', function ($query, $keyword) {
                    $query->where($query->qualifyColumn('id'), $keyword);
                })
                ->toJson();
        }


        return view('tagteams.index');
    }

    /**
     * Show the form for creating a new tag team.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TagTeam $tagTeam)
    {
        $this->authorize('create', TagTeam::class);

        return response()->view('tagteams.create', compact('tagTeam'));
    }

    /**
     * Create a new tag team.
     *
     * @param  \App\Http\Requests\StoreTagTeamRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTagTeamRequest $request)
    {
        $tagTeam = TagTeam::create($request->except(['wrestlers', 'started_at']));
        $tagTeam->employ($request->input('started_at'));
        $tagTeam->addWrestlers($request->input('wrestlers'));

        return redirect()->route('tag-teams.index');
    }

    /**
     * Show the profile of a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @return \Illuminate\Http\Response
     */
    public function show(TagTeam $tagTeam)
    {
        $this->authorize('view', $tagTeam);

        return response()->view('tagteams.show', compact('tagTeam'));
    }

    /**
     * Show the form for editing a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @return \lluminate\Http\Response
     */
    public function edit(TagTeam $tagTeam)
    {
        $this->authorize('update', $tagTeam);

        return response()->view('tagteams.edit', compact('tagTeam'));
    }

    /**
     * Update a given tag team.
     *
     * @param  \App\Http\Requests\UpdateTagTeamRequest  $request
     * @param  \App\Models\TagTeam  $tagTeam
     * @return \lluminate\Http\RedirectResponse
     */
    public function update(UpdateTagTeamRequest $request, TagTeam $tagTeam)
    {
        $tagTeam->update($request->except(['wrestlers', 'started_at']));

        $tagTeam->employ($request->input('started_at'));
        $tagTeam->wrestlerHistory()->sync($request->input('wrestlers'));

        return redirect()->route('tag-teams.index');
    }

    /**
     * Delete a tag team.
     *
     * @param  App\Models\TagTeam  $tagTeam
     * @return \lluminate\Http\RedirectResponse
     */
    public function destroy(TagTeam $tagTeam)
    {
        $this->authorize('delete', $tagTeam);

        $tagTeam->delete();

        return redirect()->route('tag-teams.index');
    }
}
