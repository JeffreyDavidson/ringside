<?php

namespace App\Models\Concerns;

use App\Models\Retirement;

trait CanBeRetired
{
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
        return $this->previousRetirements()
                    ->latest('ended_at')
                    ->limit(1);
    }

    /**
     * Scope a query to only include retired models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRetired($query)
    {
        return $this->whereHas('currentRetirement');
    }

    /**
     * Scope a query to only include unemployed models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeWithRetiredAtDate($query)
    {
        return $query->addSelect(['retired_at' => Retirement::select('started_at')
            ->where('retiree_id', $this->getTable($this).'.id')
            ->where('retiree_type', $this->getMorphClass())
            ->orderBy('started_at', 'desc')
            ->limit(1)
        ]);
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
     * @param  string|null $unretiredAt
     * @return bool
     */
    public function unretire($unretiredAt = null)
    {
        $unretiredDate = $unretiredAt ?: now();

        $this->currentRetirement()->update(['ended_at' => $unretiredDate]);
        $this->employ($unretiredAt);

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
        if ($this->isUnemployed()) {
            return false;
        }

        if ($this->isReleased()) {
            return false;
        }

        if ($this->hasFutureEmployment()) {
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
}
