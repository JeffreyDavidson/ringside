<?php

namespace App\Http\Controllers\Venues;

use App\Models\Venue;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVenueRequest;

class VenuesController extends Controller
{
    /**
     * Retrieve all venues.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
}
