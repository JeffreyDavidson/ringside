<?php

namespace App\Actions;

use App\Models\Event;
use App\Repositories\EventMatchRepository;

class AddMatchesForEvent
{
    private ?EventMatchRepository $eventMatchRepository = null;

    public function __construct(EventMatchRepository $eventMatchRepository)
    {
        $this->eventMatchRepository = $eventMatchRepository;
    }

    public function __invoke(Event $event, array $data)
    {
        foreach ($data['matches'] as $match) {
            $createdMatch = $this->eventMatchRepository->create($event, $match);

            if (count($match['titles'])) {
                foreach ($match['titles'] as $titleId) {
                    $this->eventMatchRepository->addTitleToMatch($createdMatch, $titleId);
                }
            }

            foreach ($match['referees'] as $refereeId) {
                $this->eventMatchRepository->addRefereeToMatch($createdMatch, $refereeId);
            }

            foreach ($match['competitors'] as $competitorId) {
                $this->eventMatchRepository->addCompetitorToMatch($createdMatch, $competitorId);
            }
        }
    }
}
