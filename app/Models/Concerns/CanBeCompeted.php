<?php

namespace App\Models\Concerns;

use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Builder;

trait CanBeCompeted
{
    /**
     *
     */
    public static function bootCanBeCompeted()
    {
        if (config('app.debug')) {
            $traits = class_uses_recursive(static::class);

            if (!in_array(HasCachedAttributes::class, $traits)) {
                throw new \LogicException('CanBeCompeted trait used without HasCachedAttributes trait');
            }
        }
    }

    /**
     * Determine if a manager is bookable.
     *
     * @return bool
     */
    public function getIsCompetableCachedAttribute()
    {
        return $this->status === 'competable';
    }

    /**
     * Scope a query to only include bookable managers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompetable($query)
    {
        return $query->where('status', 'competable');
    }

    /**
     * Check to see if the model can be competed for.
     *
     * @return bool
     */
    public function checkIsCompetable()
    {
        return $this->status === 'competable';
    }
}
