<?php

namespace App\Models\Concerns;

use App\Models\Retirement;
use App\Traits\HasCachedAttributes;
use App\Exceptions\CannotBeRetiredException;
use App\Exceptions\CannotBeUnretiredException;

trait CanBeRetired
{
    /**
     *
     */
    public static function bootCanBeRetired()
    {
        if (config('app.debug')) {
            $traits = class_uses_recursive(static::class);

            if (!in_array(HasCachedAttributes::class, $traits)) {
                throw new \LogicException('CanBeRetired trait used without HasCachedAttributes trait');
            }
        }
    }

    /**
     * Get the retirements of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retiree');
    }

    /**
     * Get the current retirement of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function retirement()
    {
        return $this->morphOne(Retirement::class, 'retiree')->whereNull('ended_at');
    }

    /**
     * Determine if a model is retired.
     *
     * @return bool
     */
    public function getIsRetiredCachedAttribute()
    {
        return $this->status === 'retired';
    }

    /**
     * Scope a query to only include retired models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRetired($query)
    {
        return $query->where('status', 'retired');
    }

    /**
     * Retire a model.
     *
     * @return \App\Models\Retirement
     */
    public function retire()
    {
        if ($this->checkIsPendingEmployment() || $this->checkIsRetired()) {
            throw new CannotBeRetiredException;
        }

        if ($this->checkIsSuspended()) {
            $this->reinstate();
        }

        if ($this->checkIsInjured()) {
            $this->recover();
        }

        $this->retirements()->create(['started_at' => now()]);

        return $this->touch();
    }

    /**
     * Unretire a model.
     *
     * @return bool
     */
    public function unretire()
    {
        if (! $this->checkIsRetired()) {
            throw new CannotBeUnretiredException;
        }

        $this->retirement()->update(['ended_at' => now()]);

        return $this->touch();
    }

    /**
     * @return bool
     */
    public function checkIsRetired()
    {
        return $this->retirement()->where('started_at', '<=', now())->exists();
    }
}
