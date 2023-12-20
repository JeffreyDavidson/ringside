<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\EventMatchCompetitor;
use Illuminate\Database\Eloquent\Collection;

/**
 * @template TKey of array-key
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends \Illuminate\Database\Eloquent\Collection<TKey, TModel>
 */
class EventMatchCompetitorsCollection extends Collection
{
    /**
     * Get all competitors for a match grouped by side.
     *
     * @return \Illuminate\Database\Eloquent\Collection<array-key, \Illuminate\Database\Eloquent\Collection<(int|string), \App\Models\EventMatchCompetitor>>
     */
    public function groupedBySide(): Collection
    {
        return EventMatchCompetitor::findMany($this->modelKeys())->groupBy('side_number');
    }
}
