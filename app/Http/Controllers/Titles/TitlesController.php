<?php

namespace App\Http\Controllers\Titles;

use App\Data\TitleData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Titles\StoreRequest;
use App\Http\Requests\Titles\UpdateRequest;
use App\Models\Title;
use App\Services\TitleService;

class TitlesController extends Controller
{
    private TitleService $titleService;

    /**
     * Create a new titles controller instance.
     *
     * @param  \App\Services\TitleService $titleService
     */
    public function __construct(TitleService $titleService)
    {
        $this->titleService = $titleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewList', Title::class);

        return view('titles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Title $title
     * @return \Illuminate\View\View
     */
    public function create(Title $title)
    {
        $this->authorize('create', Title::class);

        return view('titles.create', [
            'title' => $title,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Titles\StoreRequest  $request
     * @param  \App\Data\TitleData $titleData
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request, TitleData $titleData)
    {
        $this->titleService->create($titleData->fromStoreRequest($request));

        return redirect()->route('titles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Title $title
     * @return \Illuminate\View\View
     */
    public function show(Title $title)
    {
        $this->authorize('view', Title::class);

        $title->load('championships');

        return view('titles.show', compact('title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\View\View
     */
    public function edit(Title $title)
    {
        $this->authorize('update', Title::class);

        return view('titles.edit', [
            'title' => $title,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Titles\UpdateRequest  $request
     * @param  \App\Models\Title  $title
     * @param  \App\Data\TitleData $titleData
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Title $title, TitleData $titleData)
    {
        $this->titleService->update($title, $titleData->fromUpdateRequest($request));

        return redirect()->route('titles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Title $title)
    {
        $this->authorize('delete', $title);

        $this->titleService->delete($title);

        return redirect()->route('titles.index');
    }
}
