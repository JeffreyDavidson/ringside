<?php

namespace App\Models\Concerns;

use App\Models\Retirement;
use App\Traits\HasCachedAttributes;

trait CanBeRetired
{
    public static function bootCanBeRetired()
    {
        if (config('app.debug')) {
            $traits = class_uses_recursive(static::class);

            if (! in_array(HasCachedAttributes::class, $traits)) {
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
        return $this->retirements()
                    ->whereNotNull('ended_at');
    }

    /**
     * Get the previous employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousRetirement()
    {
        return $this->retirements()
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
    abstract public function retire();

    /**
     * Unretire a model.
     *
     * @return bool
     */
    public function unretire()
    {
        $this->currentRetirement()->update(['ended_at' => now()]);

        return $this->touch();
    }

    /**
     * Check to see if the model is retired.
     *
     * @return bool
     */
    public function isRetired()
    {
        return $this->currentRetirement()->exists();
    }

    /**
     * Determine if the model can be retired.
     *
     * @return bool
     */
    public function canBeRetired()
    {
        if (! $this->isEmployed()) {
            return false;
        }

        if ($this->isRetired()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the model can be retired.
     *
     * @return bool
     */
    public function canBeUnretired()
    {
        if (! $this->isRetired()) {
            return false;
        }

        return true;
    }

    /**
     * Get the current retirement of the model.
     *
     * @return App\Models\Retirement
     */
    public function getCurrentRetirementAttribute()
    {
        if (! $this->relationLoaded('currentRetirement')) {
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
        if (! $this->relationLoaded('previousRetirement')) {
            $this->setRelation('previousRetirement', $this->previousRetirement()->get());
        }

        return $this->getRelation('previousRetirement')->first();
    }
}
