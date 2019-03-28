<?php

namespace App\Http\Controllers\Events;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventsController extends Controller
{
    public function index()
    {
    }

    /**
     * Show the form for creating an event.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Event::class);

        return view('events.create');
    }

    /**
     * Create a new event.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEventRequest $request)
    {
        Event::create($request->all());

        return redirect()->route('events.index');
    }

    /**
     * Show the form for creating an event.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $this->authorize('update', Event::class);

        return view('events.edit', compact('event'));
    }

    /**
     * Create a new event.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->all());

        return redirect()->route('events.index');
    }
}
