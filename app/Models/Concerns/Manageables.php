<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\TagTeam;
use App\Models\Wrestler;

trait Manageables
{
    /**
     * Get all of the wrestlers that have been managed by model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function wrestlers(): MorphToMany
    {
        return $this->morphedByMany(Wrestler::class, 'manageable')
            ->withPivot(['hired_at', 'left_at']);
    }

    /**
     * Get the current wrestlers that is managed by model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function currentWrestlers(): MorphToMany
    {
        return $this->morphedByMany(Wrestler::class, 'manageable')
            ->wherePivotNull('left_at');
    }

    /**
     * Get all previous wrestlers that have been managed by model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function previousWrestlers(): MorphToMany
    {
        return $this->morphedByMany(Wrestler::class, 'manageable')
            ->wherePivotNotNull('left_at');
    }

    /**
     * Get all of the tag teams that have been managed by model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tagTeams(): MorphToMany
    {
        return $this->morphedByMany(TagTeam::class, 'manageable')
            ->withPivot(['hired_at', 'left_at']);
    }

    /**
     * Get all previous tag teams that have been managed by model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function currentTagTeams(): MorphToMany
    {
        return $this->morphedByMany(TagTeam::class, 'manageable')
            ->withPivot(['hired_at', 'left_at'])
            ->wherePivotNull('left_at');
    }

    /**
     * Get all previous tag teams that have been managed by model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function previousTagTeams(): MorphToMany
    {
        return $this->morphedByMany(TagTeam::class, 'manageable')
            ->withPivot(['hired_at', 'left_at'])
            ->wherePivotNotNull('left_at');
    }
}
