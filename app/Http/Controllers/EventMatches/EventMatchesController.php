<?php

namespace App\Http\Controllers\EventMatches;

use App\Actions\AddMatchesForEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventMatches\StoreRequest;
use App\Models\Event;
use App\Models\EventMatch;

class EventMatchesController extends Controller
{
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
     * @param  \App\Actions\AddMatchesForEvent $addMatchesForEvent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Event $event, StoreRequest $request, AddMatchesForEvent $addMatchesForEvent)
    {
        $addMatchesForEvent($event, $request->validated());

        return redirect()->route('events.index');
    }
}
