<?php

namespace App\Models\Concerns;

use App\Models\Employment;

trait CanBeEmployed
{
    /**
     * Get all of the employments of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function employments()
    {
        return $this->morphMany(Employment::class, 'employable');
    }

    /**
     * Get the current employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function currentEmployment()
    {
        return $this->morphOne(Employment::class, 'employable')
                    ->whereNull('ended_at');
    }

    /**
     * Get the future employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function futureEmployment()
    {
        return $this->currentEmployment()
                    ->where('started_at', '>', now());
    }

    /**
     * Get the previous employments of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousEmployments()
    {
        return $this->employments()
                    ->whereNotNull('ended_at');
    }

    /**
     * Get the previous employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousEmployment()
    {
        return $this->previousEmployments()
                    ->latest('ended_at')
                    ->limit(1);
    }

    /**
     * Scope a query to only include pending employment models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePendingEmployment($query)
    {
        return $query->whereHas('futureEmployment');
    }

    /**
     * Scope a query to only include employed models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeEmployed($query)
    {
        return $query->whereHas('currentEmployment');
    }

    /**
     * Scope a query to only include released models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeReleased($query)
    {
        return $query->whereHas('previousEmployment')
                     ->whereDoesntHave('currentRetirement');
    }

    /**
     * Scope a query to only include unemployed models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeUnemployed($query)
    {
        return $query->whereDoesntHave('employments');
    }

    /**
     * Employ a model.
     *
     * @param  Carbon|string $startedAt
     * @return bool
     */
    public function employ($startedAt = null)
    {
        $startDate = $startedAt ?? now();

        $this->employments()->updateOrCreate(['ended_at' => null], ['started_at' => $startDate]);

        return $this->touch();
    }

    /**
     * Release a model.
     *
     * @param  Carbon|string $releasedAt
     * @return bool
     */
    public function release($releasedAt = null)
    {
        if ($this->isSuspended()) {
            $this->reinstate();
        }

        if ($this->isInjured()) {
            $this->clearFromInjury();
        }

        $releaseDate = $releasedAt ?? now();
        $this->currentEmployment()->update(['ended_at' => $releaseDate]);

        return $this->touch();
    }

    /**
     * Check to see if the model is employed.
     *
     * @return bool
     */
    public function isCurrentlyEmployed()
    {
        return $this->currentEmployment instanceof Employment;
    }

    /**
     * Check to see if the model is employed.
     *
     * @return bool
     */
    public function isUnemployed()
    {
        return $this->employments->isEmpty();
    }

    /**
     * Check to see if the model has a future scheduled employment.
     *
     * @return bool
     */
    public function hasFutureEmployment()
    {
        return $this->futureEmployment instanceof Employment;
    }

    /**
     * Check to see if the model has been released.
     *
     * @return bool
     */
    public function isReleased()
    {
        return $this->employments->whereNull('ended_at')->isEmpty();
    }

    /**
     * Determine if the model can be employed.
     *
     * @return bool
     */
    public function canBeEmployed()
    {
        if ($this->isCurrentlyEmployed()) {
            return false;
        }

        if ($this->isRetired()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the model can be released.
     *
     * @return bool
     */
    public function canBeReleased()
    {
        if ($this->isUnemployed()) {
            return false;
        }

        if ($this->hasFutureEmployment()) {
            return false;
        }

        if ($this->isReleased()) {
            return false;
        }

        if ($this->isRetired()) {
            return false;
        }

        return true;
    }

    /**
     * Get the model's first employment date.
     *
     * @return string|null
     */
    public function getStartedAtAttribute()
    {
        return optional($this->employments->first())->started_at;
    }

     /**
     * Get the current employment of the model.
     *
     * @return App\Models\Employment
     */
    public function getCurrentEmploymentAttribute()
    {
        if (! $this->relationLoaded('currentEmployment')) {
            $this->setRelation('currentEmployment', $this->currentEmployment()->get());
        }

        return $this->getRelation('currentEmployment')->first();
    }

    /**
     * Get the previous employment of the model.
     *
     * @return App\Models\Employment
     */
    public function getPreviousEmploymentAttribute()
    {
        if (! $this->relationLoaded('previousEmployment')) {
            $this->setRelation('previousEmployment', $this->previousEmployment()->get());
        }

        return $this->getRelation('previousEmployment')->first();
    }

    /**
     * Get the previous employment of the model.
     *
     * @return App\Models\Employment
     */
    public function getFutureEmploymentAttribute()
    {
        if (! $this->relationLoaded('futureEmployment')) {
            $this->setRelation('futureEmployment', $this->futureEmployment()->get());
        }

        return $this->getRelation('futureEmployment')->first();
    }
}
