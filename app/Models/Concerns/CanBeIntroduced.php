<?php

namespace App\Models\Concerns;

trait CanBeIntroduced
{
    /**
     * Determine if a model has been introduced.
     *
     * @return bool
     */
    public function getIsPendingIntroductionAttribute()
    {
        return is_null($this->introduced_at) || $this->introduced_at->isFuture();
    }

    /**
     * Retrieve a formatted introduced at date timestamp.
     *
     * @return string
     */
    public function getFormattedIntroducedAtAttribute()
    {
        return $this->introduced_at->toDateString();
    }

    /**
     * Scope a query to only include pending introduced models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePendingIntroduction($query)
    {
        $query->where('introduced_at', '>', now());
    }

    /**
     * Introduce a model.
     *
     * @return $this
     */
    public function introduce()
    {
        $this->update(['introduced_at' => now()]);

        return $this;
    }
}
