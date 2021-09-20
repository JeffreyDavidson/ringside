<?php

namespace App\Http\Controllers\EventMatches;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventMatches\StoreRequest;
use App\Http\Requests\EventMatches\UpdateRequest;
use App\Models\Event;
use App\Models\EventMatch;
use App\Services\EventMatchService;
use App\Services\EventService;

class EventMatchesController extends Controller
{
    public EventService $eventService;

    /**
     * Create a new events controller instance.
     *
     * @param \App\Services\EventService $eventService
     */
    public function __construct(EventMatchService $eventMatchService)
    {
        $this->eventMatchService = $eventMatchService;
    }

    /**
     * Show the form for creating a new event.
     *
     * @param  \App\Models\Event $event
     * @return \Illuminate\View\View
     */
    public function create(Event $event)
    {
        $this->authorize('create', EventMatch::class);

        return view('matches.create', compact('event'));
    }

    /**
     * Create a new event.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Http\Requests\StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Event $event, StoreRequest $request)
    {
        // $this->eventMatchService->create($event, $request->validated());
        $this->eventRepository->addMatches($event, $request->validated());

        return redirect()->route('events.index');
    }

    /**
     * Show the event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);

        if (! is_null($event->venue_id)) {
            $event->load('venue');
        }

        return response()->view('events.show', compact('event'));
    }

    /**
     * Show the form for editing a given event.
     *
     * @param  \App\Models\Event $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    /**
     * Update an event.
     *
     * @param  \App\Http\Requests\Events\UpdateRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Event $event)
    {
        $this->eventService->update($event, $request->validated());

        return redirect()->route('events.index');
    }

    /**
     * Delete an event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $this->eventService->delete($event);

        return redirect()->route('events.index');
    }
}
