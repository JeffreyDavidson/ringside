<?php

namespace App\Http\Controllers\Matches;

use App\Models\Event;
use App\Models\Wrestler;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMatchesRequest;

class MatchesController extends Controller
{
    public function index(Event $event)
    {
    }

    /**
     * Show the form for creating a match for an event.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        $this->authorize('addMatches', $event);

        return view('matches.create');
    }

    /**
     * Create matches for an event.
     *
     * @param  \App\Http\Requests\StoreMatchesRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreMatchesRequest $request, Event $event)
    {
        foreach ($request->input('matches') as $match) {
            $matchObject = $event->matches()->create([
                'match_type_id' => $match['match_type_id'],
                'preview' => $match['preview'],
            ]);

            foreach ($match['competitors'] as $sideNumber => $competitorTypes) {
                foreach ($competitorTypes as $competitorType => $competitors) {
                    if ($competitorType === 'wrestlers') {
                        $wrestlers = Wrestler::find($competitors);

                        foreach ($wrestlers as $wrestler) {
                            $matchObject->wrestlers()->attach($wrestler, ['side_number' => $sideNumber]);
                        }
                    }

                    if ($competitorType === 'tagteams') {
                        $tagteams = TagTeam::find($competitors);

                        foreach ($tagteams as $tagteam) {
                            $matchObject->tagteams()->attach($tagteam, ['side_number' => $sideNumber]);
                        }
                    }
                }
            }
        }

        return redirect()->route('events.matches.index', $event);
    }
}
