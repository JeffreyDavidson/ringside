<?php

namespace App\Models\Concerns;

use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Builder;

trait CanBeBooked
{
    /**
     * 
     */
    public static function bootCanBeBooked()
    {
        if (config('app.debug')) {
            $traits = class_uses_recursive(static::class);

            if (!in_array(HasCachedAttributes::class, $traits)) {
                throw new \LogicException('CanBeBooked trait used without HasCachedAttributes trait');
            }
        }
    }

    /**
     * Determine if a manager is bookable.
     *
     * @return bool
     */
    public function getIsBookableAttribute()
    {
        return $this->status === 'bookable';
    }

    /**
     * Scope a query to only include bookable managers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBookable($query)
    {
        return $query->where('status', 'bookable');
    }

    /**
     * @return bool
     */
    public function checkIsBookable()
    {
        return $this->employments()
                    ->where('started_at', '<=', now())
                    ->whereNull('ended_at')
                    ->exists();
    }
}
