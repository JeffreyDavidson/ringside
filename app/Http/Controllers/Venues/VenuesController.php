<?php

namespace App\Http\Controllers\Venues;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVenueRequest;
use App\Http\Requests\UpdateVenueRequest;
use App\Models\Venue;

class VenuesController extends Controller
{
    /**
     * View a list of venues.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewList', Venue::class);

        return view('venues.index');
    }

    /**
     * Show the form for creating a venue.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Venue::class);

        return view('venues.create');
    }

    /**
     * Create a new venue.
     *
     * @param  \App\Http\Requests\StoreVenueRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreVenueRequest $request)
    {
        Venue::create($request->all());

        return redirect()->route('venues.index');
    }

    /**
     * Show the venue.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        $this->authorize('view', $venue);

        return view('venues.show', compact('venue'));
    }

    /**
     * Show the form for editing a venue.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue)
    {
        $this->authorize('update', Venue::class);

        return view('venues.edit', compact('venue'));
    }

    /**
     * Update a given venue.
     *
     * @param  \App\Http\Requests\UpdateVenueRequest  $request
     * @param  \App\Models\Venue  $venue
     * @return \lluminate\Http\RedirectResponse
     */
    public function update(UpdateVenueRequest $request, Venue $venue)
    {
        $venue->update($request->all());

        return redirect()->route('venues.index');
    }
}
