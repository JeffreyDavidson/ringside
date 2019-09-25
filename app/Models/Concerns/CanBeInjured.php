<?php

namespace App\Models\Concerns;

use App\Models\Injury;
use App\Traits\HasCachedAttributes;
use App\Exceptions\CannotBeInjuredException;
use App\Exceptions\CannotBeRecoveredException;

trait CanBeInjured
{
    /**
     *
     */
    public static function bootCanBeInjured()
    {
        if (config('app.debug')) {
            $traits = class_uses_recursive(static::class);

            if (!in_array(HasCachedAttributes::class, $traits)) {
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
    public function injury()
    {
        return $this->morphOne(Injury::class, 'injurable')->whereNull('ended_at');
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
        if ($this->checkIsPendingEmployment() ||
            $this->checkIsRetired() ||
            $this->checkIsInjured() ||
            $this->checkIsSuspended()
        ) {
            throw new CannotBeInjuredException;
        }

        $this->injuries()->create(['started_at' => now()]);
        $this->touch();
    }

    /**
     * Recover a model.
     *
     * @return bool
     */
    public function recover()
    {
        if (! $this->checkIsInjured()) {
            throw new CannotBeRecoveredException;
        }

        $this->injury()->update(['ended_at' => now()]);

        return $this->touch();
    }

    /**
     * @return bool
     */
    public function checkIsInjured()
    {
        return $this->injury()->where('started_at', '<=', now())->exists();
    }
}
