<?php

namespace App\Models\Concerns;

use App\Models\Suspension;

trait CanBeSuspended
{
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
        return $this->suspensions()
                    ->whereNotNull('ended_at');
    }

    /**
     * Get the previous employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousSuspension()
    {
        return $this->previousSuspensions()
                    ->latest('ended_at')
                    ->limit(1);
    }

    /**
     * Scope a query to only include suspended models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuspended($query)
    {
        return $this->whereHas('currentSuspension');
    }

    /**
     * Suspend a model.
     *
     * @param  string|null $suspendedAt
     * @return \App\Models\Suspension
     */
    public function suspend($suspendedAt = null)
    {
        $suspensionDate = $suspendedAt ?? now();

        $this->suspensions()->create(['started_at' => $suspensionDate]);

        return $this->touch();
    }

    /**
     * Reinstate a model.
     *
     * @param  string|null $reinstatedAt
     * @return bool
     */
    public function reinstate($reinstatedAt = null)
    {
        $reinstatedDate = $reinstatedAt ?: now();

        $this->currentSuspension()->update(['ended_at' => $reinstatedDate]);

        return $this->touch();
    }

    /**
     * @return bool
     */
    public function isSuspended()
    {
        return $this->currentSuspension instanceof Suspension;
    }

    /**
     * Determine if the model can be retired.
     *
     * @return bool
     */
    public function canBeSuspended()
    {
        if ($this->isUnemployed()) {
            return false;
        }

        if ($this->isReleased()) {
            return false;
        }

        if ($this->hasFutureEmployment()) {
            return false;
        }

        if ($this->isSuspended()) {
            return false;
        }

        if ($this->isRetired()) {
            return false;
        }

        if ($this->isInjured()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the model can be reinstated.
     *
     * @return bool
     */
    public function canBeReinstated()
    {
        if (! $this->isSuspended()) {
            return false;
        }

        return true;
    }
}
