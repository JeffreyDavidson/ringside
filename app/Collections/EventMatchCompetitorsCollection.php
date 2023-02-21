<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\EventMatchCompetitor;
use Illuminate\Database\Eloquent\Collection;

class EventMatchCompetitorsCollection extends Collection
{
    /**
     * Get all competitors for a match grouped by side.
     */
    public function groupedBySide(): Collection
    {
        return EventMatchCompetitor::findMany($this->modelKeys())->groupBy('side_number');
    }

    /**
     * Get all competitors for a match grouped by their type.
     */
    public function groupedByCompetitorType(): Collection
    {
        return $this->groupBy(function ($group) {
            return match ($group->competitor_type) {
                \App\Models\Wrestler::class => 'wrestlers',
                \App\Models\TagTeam::class => 'tag_teams'
            };
        });
    }
}
