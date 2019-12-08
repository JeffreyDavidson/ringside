<?php

namespace App\Models\Concerns;

use App\Models\Suspension;
use App\Traits\HasCachedAttributes;

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
    public function currentSuspension()
    {
        return $this->morphOne(Suspension::class, 'suspendable')
                    ->whereNull('ended_at');
    }

    /**
     * Get the previous retirements of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousSuspensions()
    {
        return $this->morphMany(Suspension::class, 'suspendable')
                    ->whereNotNull('ended_at');
    }

    /**
     * Get the previous employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousSuspension()
    {
        return $this->morphMany(Suspension::class, 'suspendable')
                    ->whereNotNull('ended_at')
                    ->latest('ended_at')
                    ->limit(1);
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
        $this->currentSuspension()->update(['ended_at' => now()]);

        return $this->touch();
    }

    /**
     * @return bool
     */
    public function isSuspended()
    {
        return $this->currentSuspension()->exists();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCurrentSuspensionAttribute()
    {
        if (!$this->relationLoaded('currentSuspension')) {
            $this->setRelation('currentSuspension', $this->currentSuspension()->get());
        }

        return $this->getRelation('currentSuspension')->first();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPreviousSuspensionAttribute()
    {
        if (!$this->relationLoaded('previousSuspension')) {
            $this->setRelation('previousSuspension', $this->previousSuspension()->get());
        }

        return $this->getRelation('previousSuspension')->first();
    }
}
