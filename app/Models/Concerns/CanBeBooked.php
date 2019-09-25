<?php

namespace App\Models\Concerns;

use App\Traits\HasCachedAttributes;

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
    public function getIsBookableCachedAttribute()
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
        $isEmployed = $this->employment()->where('started_at', '<=', now())->exists();
        $isNotSuspended = $this->suspension()->whereNotNull('ended_at')->exists();
        $isNotInjured = $this->injury()->whereNotNull('ended_at')->exists();
        $isNotRetired = $this->retirement()->whereNotNull('ended_at')->exists();

        return $isEmployed && $isNotSuspended && $isNotInjured && $isNotRetired;
    }
}
