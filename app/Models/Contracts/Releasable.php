<?php

namespace App\Models\Contracts;

interface Releasable
{
    /**
     * Scope a query to only include released models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeReleased($query);

    /**
     * Scope a query to include model's released at date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeWithReleasedAtDate($query);

    /**
     * Scope a query to order models by current released at date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeOrderByCurrentReleasedAtDate($query);

    /**
     * Check to see if the model has been released.
     *
     * @return bool
     */
    public function isReleased();

    /**
     * Determine if a model can be released.
     *
     * @return bool
     */
    public function canBeReleased();
}
