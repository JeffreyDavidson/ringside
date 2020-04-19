<?php

namespace App\Models\Concerns;

use App\Models\Activation;

trait CanBeActivated
{
    /**
     * Get all of the activations of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activations()
    {
        return $this->morphMany(Activation::class, 'activatable');
    }

    /**
     * Get the current activation of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function currentActivation()
    {
        return $this->morphOne(Activation::class, 'activatable')
                    ->where('started_at', '<=', now())
                    ->whereNull('ended_at')
                    ->limit(1);
    }

    /**
     * Get the future activation of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function futureActivation()
    {
        return $this->morphOne(Activation::class, 'activatable')
                    ->where('started_at', '>', now())
                    ->whereNull('ended_at')
                    ->limit(1);
    }

    /**
     * Get the previous activations of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousActivations()
    {
        return $this->activations()
                    ->whereNotNull('ended_at');
    }

    /**
     * Get the previous activation of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousActivation()
    {
        return $this->previousActivations()
                    ->latest('ended_at')
                    ->limit(1);
    }

    /**
     * Scope a query to only include pending activation models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePendingActivation($query)
    {
        return $query->whereHas('futureActivation');
    }

    /**
     * Scope a query to only include activated models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeActive($query)
    {
        return $query->whereHas('currentActivation');
    }

    /**
     * Scope a query to only include deactivated models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeInactive($query)
    {
        return $query->whereHas('previousActivation')
                    ->whereDoesntHave('currentActivation')
                    ->whereDoesntHave('currentRetirement');
    }

    /**
     * Scope a query to only include unactivated models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeUnactivated($query)
    {
        return $query->whereDoesntHave('activations');
    }

    /**
     * Scope a query to only include unactivated models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeWithActivatedAtDate($query)
    {
        return $query->addSelect(['activated_at' => Activation::select('started_at')
            ->whereColumn('activatable_id', $this->getTable().'.id')
            ->where('activatable_type', $this->getMorphClass())
            ->orderBy('started_at', 'desc')
            ->limit(1)
        ])->withCasts(['activated_at' => 'datetime']);
    }

    /**
     * Scope a query to only include unactivated models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeWithDeactivatedAtDate($query)
    {
        return $query->addSelect(['deactivated_at' => Activation::select('ended_at')
            ->whereColumn('activatable_id', $this->getTable().'.id')
            ->where('activatable_type', $this->getMorphClass())
            ->orderBy('ended_at', 'desc')
            ->limit(1)
        ])->withCasts(['deactivated_at' => 'datetime']);
    }

    /**
     * Activate a model.
     *
     * @param  Carbon|string $startedAt
     * @return bool
     */
    public function activate($startedAt = null)
    {
        $startDate = $startedAt ?? now();

        $this->activations()->updateOrCreate(['ended_at' => null], ['started_at' => $startDate]);

        return $this->touch();
    }

    /**
     * Deactivate a model.
     *
     * @param  Carbon|string $deactivatedAt
     * @return bool
     */
    public function deactivate($deactivatedAt = null)
    {
        $deactivatedate = $deactivatedAt ?? now();

        $this->currentActivation()->update(['ended_at' => $deactivatedate]);

        return $this->touch();
    }

    /**
     * Check to see if the model is activated.
     *
     * @return bool
     */
    public function isCurrentlyActivated()
    {
        return $this->currentActivation()->exists();
    }

    /**
     * Check to see if the model is activated.
     *
     * @return bool
     */
    public function isUnactivated()
    {
        return $this->activations->isEmpty();
    }

    /**
     * Check to see if the model is activated.
     *
     * @return bool
     */
    public function hasPreviouslyBeenActivated()
    {
        return $this->activations->isNotEmpty();
    }

    /**
     * Check to see if the model has a future scheduled activation.
     *
     * @return bool
     */
    public function hasFutureActivation()
    {
        return $this->futureActivation()->exists();
    }

    /**
     * Check to see if the model has been deactivated.
     *
     * @return bool
     */
    public function isDeactivated()
    {
        return $this->previousActivation()->exists() &&
                $this->currentActivation()->doesntExist() &&
                $this->currentRetirement()->doesntExist();
    }

    /**
     * Determine if the model can be activated.
     *
     * @return bool
     */
    public function canBeActivated()
    {
        if ($this->isCurrentlyActivated()) {
            return false;
        }

        if ($this->isRetired()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the model can be deactivated.
     *
     * @return bool
     */
    public function canBeDeactivated()
    {
        if ($this->isUnactivated()) {
            return false;
        }

        if ($this->hasFutureActivation()) {
            return false;
        }

        if ($this->isDeactivated()) {
            return false;
        }

        if ($this->isRetired()) {
            return false;
        }

        return true;
    }

    /**
     * Retrieve a introduced at date timestamp.
     *
     * @return string
     */
    public function getActivatedAtAttribute()
    {
        return $this->activations()->first()->started_at;
    }

    /**
    * Get the current activation of the model.
    *
    * @return App\Models\Activation
    */
    public function getCurrentActivationAttribute()
    {
        if (! $this->relationLoaded('currentActivation')) {
            $this->setRelation('currentActivation', $this->currentActivation()->get());
        }

        return $this->getRelation('currentActivation')->first();
    }

    /**
     * Get the previous activation of the model.
     *
     * @return App\Models\Activation
     */
    public function getPreviousActivationAttribute()
    {
        if (! $this->relationLoaded('previousActivation')) {
            $this->setRelation('previousActivation', $this->previousActivation()->get());
        }

        return $this->getRelation('previousActivation')->first();
    }

    /**
     * Get the previous activation of the model.
     *
     * @return App\Models\Activation
     */
    public function getFutureActivationAttribute()
    {
        if (! $this->relationLoaded('futureActivation')) {
            $this->setRelation('futureActivation', $this->futureActivation()->get());
        }

        return $this->getRelation('futureActivation')->first();
    }
}
