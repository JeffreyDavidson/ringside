<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\EventMatch;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasMatches
{
    /**
     * Get the event matches the model have participated in.
     */
    public function eventMatches(): MorphToMany
    {
        return $this->morphToMany(EventMatch::class, 'competitor', 'event_match_competitors');
    }
}
