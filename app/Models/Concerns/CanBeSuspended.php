<?php

namespace App\Models\Concerns;

use App\Models\Suspension;
use App\Traits\HasCachedAttributes;
use App\Exceptions\CannotBeSuspendedException;
use App\Exceptions\CannotBeReinstatedException;

trait CanBeSuspended
{
    /**
     *
     */
    public static function bootCanBeSuspended()
    {
        if (config('app.debug')) {
            $traits = class_uses_recursive(static::class);

            if (!in_array(HasCachedAttributes::class, $traits)) {
                throw new \LogicException('CanBeSuspended trait used without HasCachedAttributes trait');
            }
        }
    }

    /**
     * Get the suspensions of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function suspensions()
    {
        return $this->morphMany(Suspension::class, 'suspendable');
    }

    /**
     * Get the current suspension of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function suspension()
    {
        return $this->morphOne(Suspension::class, 'suspendable')->whereNull('ended_at');
    }

    /**
     * Determine if a model is suspended.
     *
     * @return bool
     */
    public function getIsSuspendedCachedAttribute()
    {
        return $this->status === 'suspended';
    }

    /**
     * Scope a query to only include suspended models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Suspend a model.
     *
     * @return \App\Models\Suspension
     */
    public function suspend()
    {
        if ($this->checkIsPendingEmployment() ||
            $this->checkIsRetired() ||
            $this->checkIsInjured() ||
            $this->checkIsSuspended()
        ) {
            throw new CannotBeSuspendedException;
        }

        $this->suspensions()->create(['started_at' => now()]);

        return $this->touch();
    }

    /**
     * Reinstate a model.
     *
     * @return bool
     */
    public function reinstate()
    {
        if (! $this->checkIsSuspended()) {
            throw new CannotBeReinstatedException;
        }

        $this->suspension()->update(['ended_at' => now()]);

        return $this->touch();
    }

    /**
     * @return bool
     */
    public function checkIsSuspended()
    {
        return $this->suspension()->where('started_at', '<=', now())->exists();
    }
}
