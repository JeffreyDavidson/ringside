<?php

namespace App\Models\Concerns;

use App\Models\Employment;
use App\Traits\HasCachedAttributes;
use App\Exceptions\CannotBeFiredException;


trait CanBeEmployed
{
    public static function bootCanBeEmployed()
    {
        if (config('app.debug')) {
            $traits = class_uses_recursive(static::class);

            if (!in_array(HasCachedAttributes::class, $traits)) {
                throw new \LogicException('CanBeEmployed trait used without HasCachedAttributes trait');
            }
        }
    }

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
                    ->where('started_at', '<=', now())
                    ->whereNull('ended_at');
    }

    /**
     * Get the previous employments of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousEmployments()
    {
        return $this->morphMany(Employment::class, 'employable')
                    ->whereNotNull('ended_at');
    }

    /**
     * Get the previous employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousEmployment()
    {
        return $this->morphMany(Employment::class, 'employable')
                    ->whereNotNull('ended_at')
                    ->latest('ended_at')
                    ->limit(1);
    }

    /**
     * Determine if a model is employed.
     *
     * @return bool
     */
    public function getIsEmployedCachedAttribute()
    {
        return $this->isEmployed();
    }

    /**
     * Scope a query to only include pending employment models.
     * These model have not been employed.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function getIsPendingEmploymentCachedAttribute()
    {
        if (!$this->currentEmployment) {
            return true;
        }

        return $this->whereHas('currentEmployment', function ($query) {
            $query->where('started_at', '>', now());
        })->exists();
    }

    /**
     * Scope a query to only include pending employment models.
     * These model have not been employed.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePendingEmployment($query)
    {
        return $query->where('status', 'pending-employment');
    }

    /**
     * Scope a query to only include employed models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeEmployed($query)
    {
        return $query->where('status', '!=', 'pending-employment');
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
     * Fire a model.
     *
     * @param  Carbon|string $startedAt
     * @return bool
     */
    public function fire($firedAt = null)
    {
        if ($this->isPendingEmployment() || $this->isRetired()) {
            throw new CannotBeFiredException;
        }

        if ($this->isSuspended()) {
            $this->reinstate();
        }

        if ($this->isInjured()) {
            $this->recover();
        }

        $fireDate = $firedAt ?? now();
        $this->currentEmployment()->update(['ended_at' => $fireDate]);

        return $this->touch();
    }

    /**
     * Check to see if the model is employed.
     *
     * @return bool
     */
    public function isEmployed()
    {
        return $this->currentEmployment()->exists();
    }

    /**
     * Check to see if the model is pending employment.
     *
     * @return bool
     */
    public function isPendingEmployment()
    {
        return $this->currentEmployment()->doesntExist();
    }

    /**
     * Get the current employment of the model.
     *
     * @return App\Models\Employment
     */
    public function getCurrentEmploymentAttribute()
    {
        if (!$this->relationLoaded('currentEmployment')) {
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
        if (!$this->relationLoaded('previousEmployment')) {
            $this->setRelation('previousEmployment', $this->previousEmployment()->get());
        }

        return $this->getRelation('previousEmployment')->first();
    }
}
