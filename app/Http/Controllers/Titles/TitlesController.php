<?php

namespace App\Http\Controllers\Titles;

use App\Models\Title;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTitleRequest;
use App\Http\Requests\UpdateTitleRequest;

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

    /**
     * Show the form for editing a title.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Title $title)
    {
        $this->authorize('update', Title::class);

        return response()->view('titles.edit', compact('title'));
    }

    /**
     * Create a new title.
     *
     * @param  \App\Http\Requests\UpdateTitleRequest  $request
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTitleRequest $request, Title $title)
    {
        $title->update($request->all());

        return redirect()->route('titles.index');
    }
}
