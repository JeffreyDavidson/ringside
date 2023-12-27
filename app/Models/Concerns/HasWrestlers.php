<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Wrestler;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasWrestlers
{
    /**
     * Get the wrestlers that have been tag team partners of the tag team.
     *
     * @return BelongsToMany<Wrestler>
     */
    public function wrestlers(): BelongsToMany
    {
        return $this->belongsToMany(Wrestler::class, 'tag_team_wrestler')
            ->withPivot('joined_at', 'left_at');
    }

    /**
     * Get current wrestlers of the tag team.
     *
     * @return BelongsToMany<Wrestler>
     */
    public function currentWrestlers(): BelongsToMany
    {
        return $this->wrestlers()
            ->wherePivotNull('left_at');
    }

    /**
     * Get previous tag team partners of the tag team.
     *
     * @return BelongsToMany<Wrestler>
     */
    public function previousWrestlers(): BelongsToMany
    {
        return $this->wrestlers()
            ->wherePivotNotNull('left_at');
    }

    /**
     * Get the combined weight of both tag team partners in a tag team.
     *
     * @return Attribute<string, never>
     */
    public function combinedWeight(): Attribute
    {
        return new Attribute(
            get: fn () => $this->currentWrestlers->sum('weight')
        );
    }

    /**
     * Get the current wrestlers names.
     *
     * @return Attribute<string, never>
     */
    public function currentWrestlersNames(): Attribute
    {
//        $names = match ($this->currentWrstlers->count()) {
//            0 => 'No Users Assigned',
//            1 => $this->currentWrstlers->first()->name.' and TBD',
//            2 => $this->currentWrstlers->pluck('name')->join(', ', ' and '),
//        };

//        return new Attribute(
//            get: fn () => $names
//        );

        if ($this->currentWrestlers->isEmpty()) {
            return new Attribute(
                get: fn () => 'No Users Assigned'
            );
        }

        if ($this->currentWrestlers->containsOneItem()) {
            return new Attribute(
                get: fn () => "{$this->currentWrestlers->first()->name} and TBD"
            );
        }

        return new Attribute(
            get: fn () => $this->currentWrestlers->pluck('name')->join(', ', ' and ')
        );
    }
}
