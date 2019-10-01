<?php

namespace App\Models\Concerns;

use App\Models\Employment;
use App\Traits\HasCachedAttributes;

trait CanBeEmployed
{
    /**
     *
     */
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
     * Determine if a model is employed.
     *
     * @return bool
     */
    public function getIsEmployedCachedAttribute()
    {
        return $this->currentEmployment()->exists();
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
     * @param Carbon|string $startedAt
     *
     * @return bool
     */
    public function employ($startedAt = null)
    {
        $startDate = $startedAt ?? now();
        $this->employments()->updateOrCreate(['ended_at' => null], ['started_at' => $startDate]);

        return $this->touch();
    }

    /**
     * @return bool
     */
    public function checkIsEmployed()
    {
        return $this->currentEmployment()->exists();
    }

    /**
     * @return bool
     */
    public function checkIsPendingEmployment()
    {
        return $this->currentEmployment()->doesntExist();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCurrentEmploymentAttribute()
    {
        if (!$this->relationLoaded('currentEmployment')) {
            $this->setRelation('currentEmployment', $this->currentEmployment()->get());
        }

        return $this->getRelation('currentEmployment')->first();
    }
}
