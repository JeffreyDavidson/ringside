<?php

namespace App\Models\Concerns;

use App\Models\Retirement;
use App\Traits\HasCachedAttributes;
use App\Exceptions\CannotBeRetiredException;
use App\Exceptions\CannotBeUnretiredException;

trait CanBeRetired
{
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
    public function currentRetirement()
    {
        return $this->morphOne(Retirement::class, 'retiree')
                    ->where('started_at', '<=', now())
                    ->whereNull('ended_at');
    }

    /**
     * Get the previous retirements of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousRetirements()
    {
        return $this->morphMany(Retirement::class, 'retiree')
                    ->whereNotNull('ended_at');
    }

    /**
     * Get the previous employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousRetirement()
    {
        return $this->morphMany(Retirement::class, 'retiree')
                    ->whereNotNull('ended_at')
                    ->latest('ended_at')
                    ->limit(1);
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
     * @return bool
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

        $this->currentRetirement()->update(['ended_at' => now()]);

        return $this->touch();
    }

    /**
     * Check to see if the model is retired.
     *
     * @return bool
     */
    public function checkIsRetired()
    {
        return $this->currentRetirement()->exists();
    }

    /**
     * Get the current retirement of the model.
     *
     * @return App\Models\Retirement
     */
    public function getCurrentRetirementAttribute()
    {
        if (!$this->relationLoaded('currentRetirement')) {
            $this->setRelation('currentRetirement', $this->currentRetirement()->get());
        }

        return $this->getRelation('currentRetirement')->first();
    }

    /**
     * Get the previous retirement of the model.
     *
     * @return App\Models\Retirement
     */
    public function getPreviousRetirementAttribute()
    {
        if (!$this->relationLoaded('previousRetirement')) {
            $this->setRelation('previousRetirement', $this->previousRetirement()->get());
        }

        return $this->getRelation('previousRetirement')->first();
    }
}
