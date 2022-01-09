<?php

namespace App\Models\Concerns;

use App\Models\Stable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

trait CanJoinStables
{
    use HasRelationships;

    /**
     * Get the stables the model has been belonged to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function stables()
    {
        return $this->morphMany(Stable::class, 'member', 'stable_members');
    }

    /**
     * Get the current stable the member belongs to.
     *
     * @return \Staudenmeir\EloquentHasManyDeep\HasOneDeep
     */
    public function currentStable()
    {
        return $this
            ->hasOneDeep(
                Stable::class,
                [static::class, 'stable_members'],
                ['id', ['member_type', 'member_id'], 'id'],
                [null, null, 'stable_id']
            )
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
        $this->currentStable->isNot($stable);
    }
}
