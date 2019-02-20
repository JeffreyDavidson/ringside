<?php

namespace App\Http\Controllers;

use App\TagTeam;
use App\Http\Requests\StoreTagTeamRequest;

class TagTeamsController extends Controller
{
    /**
     * Show the form for creating a new tag team.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', TagTeam::class);

        return response()->view('tagteams.create');
    }

    /**
     * Create a new tag team.
     *
     * @param  \App\Http\Requests\StoreTagTeamRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTagTeamRequest $request)
    {
        $tagteam = TagTeam::create($request->except('wrestlers'));

        $tagteam->addWrestlers($request->input('wrestlers'));

        return redirect()->route('tagteams.index');
    }
}
