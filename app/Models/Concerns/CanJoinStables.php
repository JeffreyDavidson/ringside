<?php

namespace App\Models\Concerns;

use App\Models\Stable;
use Fidum\EloquentMorphToOne\HasMorphToOne;

trait CanJoinStables
{
    use HasMorphToOne;

    /**
     * Get the stables the model has been belonged to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function stables()
    {
        return $this->morphToMany(Stable::class, 'member', 'stable_members')
            ->withPivot(['joined_at', 'left_at']);
    }

    /**
     * Get the current stable the member belongs to.
     *
     * @return \Staudenmeir\EloquentHasManyDeep\HasOneDeep
     */
    public function currentStable()
    {
        return $this->morphToOne(Stable::class, 'member', 'stable_members')
            ->withPivot(['joined_at', 'left_at'])
            ->wherePivotNull('left_at');
    }

    /**
     * Get the previous stables the member has belonged to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousStables()
    {
        return $this->morphMany(Stable::class, 'members')
            ->wherePivot('joined_at', '<', now())
            ->wherePivotNotNull('left_at');
    }

    public function isNotCurrentlyInStable(Stable $stable)
    {
        return ! $this->currentStable || $this->currentStable->isNot($stable);
    }
}
