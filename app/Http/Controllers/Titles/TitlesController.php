<?php

namespace App\Http\Controllers\Titles;

use App\Models\Title;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTitleRequest;

class TitlesController extends Controller
{
    /**
     *
     *
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new title.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Title::class);

        return response()->view('titles.create');
    }

    /**
     * Create a new title.
     *
     * @param  \App\Http\Requests\StoreTitleRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTitleRequest $request)
    {
        $title = Title::create($request->all());

        return redirect()->route('titles.index');
    }
}
