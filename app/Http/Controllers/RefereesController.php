<?php

namespace App\Http\Controllers;

use App\Referee;
use App\Http\Requests\StoreRefereeRequest;

class RefereesController extends Controller
{
    /**
     * Show the form for creating a new referee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Referee::class);

        return response()->view('referees.create');
    }

    /**
     * Create a new referee.
     *
     * @param  \App\Http\Requests\StoreRefereeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRefereeRequest $request)
    {
        $referee = Referee::create($request->all());

        return redirect()->route('referees.index');
    }
}
