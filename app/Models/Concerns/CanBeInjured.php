<?php

namespace App\Models\Concerns;

use App\Models\Injury;
use App\Traits\HasCachedAttributes;

trait CanBeInjured
{
    public static function bootCanBeInjured()
    {
        if (config('app.debug')) {
            $traits = class_uses_recursive(static::class);

            if (! in_array(HasCachedAttributes::class, $traits)) {
                throw new \LogicException('CanBeInjured trait used without HasCachedAttributes trait');
            }
        }
    }

    /**
     * Get the injuries of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function injuries()
    {
        return $this->morphMany(Injury::class, 'injurable');
    }

    /**
     * Get the current injury of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function currentInjury()
    {
        return $this->morphOne(Injury::class, 'injurable')
                    ->where('started_at', '<=', now())
                    ->whereNull('ended_at');
    }

    /**
     * Get the previous injuries of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousInjuries()
    {
        return $this->injuries()
                    ->whereNotNull('ended_at');
    }

    /**
     * Get the previous employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousInjury()
    {
        return $this->injuries()
                    ->whereNotNull('ended_at')
                    ->latest('ended_at')
                    ->limit(1);
    }

    /**
     * Determine if a model is injured.
     *
     * @return bool
     */
    public function getIsInjuredCachedAttribute()
    {
        return $this->status === 'injured';
    }

    /**
     * Scope a query to only include injured models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInjured($query)
    {
        return $query->where('status', 'injured');
    }

    /**
     * Injure a model.
     *
     * @return \App\Models\Injury
     */
    public function injure()
    {
        $this->injuries()->create(['started_at' => now()]);

        return $this->touch();
    }

    /**
     * Recover a model.
     *
     * @return bool
     */
    public function heal()
    {
        $this->currentInjury()->update(['ended_at' => now()]);

        return $this->touch();
    }

    /**
     * Check to see if the currently model is injured.
     *
     * @return bool
     */
    public function isInjured()
    {
        return $this->currentInjury()->exists();
    }

    /**
     * Determine if the model can be injured.
     *
     * @return bool
     */
    public function canBeInjured()
    {
        if (! $this->isEmployed()) {
            return false;
        }

        if ($this->isInjured()) {
            return false;
        }

        if ($this->isRetired()) {
            return false;
        }

        if ($this->isSuspended()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the model can be marked as healed.
     *
     * @return bool
     */
    public function canBeMarkedAsHealed()
    {
        if (! $this->isInjured()) {
            return false;
        }

        return true;
    }

    /**
     * Get the current injury of the model.
     *
     * @return App\Models\Injury
     */
    public function getCurrentInjuryAttribute()
    {
        if (! $this->relationLoaded('currentInjury')) {
            $this->setRelation('currentInjury', $this->currentInjury()->get());
        }

        return $this->getRelation('currentInjury')->first();
    }

    /**
     * Get the previous injury of the model.
     *
     * @return App\Models\Injury
     */
    public function getPreviousInjuryAttribute()
    {
        if (! $this->relationLoaded('previousInjury')) {
            $this->setRelation('previousInjury', $this->previousInjury()->get());
        }

        return $this->getRelation('previousInjury')->first();
    }
}
