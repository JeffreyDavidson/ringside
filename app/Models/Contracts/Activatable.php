<?php

namespace App\Models\Contracts;

interface Activatable
{
    /**
     * Get all of the activations of the model.
     */
    public function activations();

    /**
     * Get the current activation of the model.
     */
    public function currentActivation();

    /**
     * Get the first activation of the model.
     */
    public function firstActivation();

    /**
     * Get the future activation of the model.
     */
    public function futureActivation();

    /**
     * Get the previous activation of the model.
     */
    public function previousActivation();

    /**
     * Get the previous activations of the model.
     */
    public function previousActivations();

    /**
     * Scope a query to only include active models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeActive($query);

    /**
     * Scope a query to only include future activated models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeWithFutureActivation($scope);

    /**
     * Scope a query to only include inactive models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeInactive($query);

    /**
     * Scope a query to only include unactivated models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeUnactivated($query);

    /**
     * Scope a query to include current activation date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeWithFirstActivatedAtDate($query);

    /**
     * Scope a query to order by the models first activation date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeOrderByFirstActivatedAtDate($query);

    /**
     * Check to see if the model is currently active.
     */
    public function isCurrentlyActivated();

    /**
     * Check to see if the model has been activated.
     */
    public function hasActivations();

    /**
     * Check to see if the model is unactivated.
     */
    public function isNotActivated();

    /**
     * Check to see if the model has a future activation.
     */
    public function hasFutureActivation();

    /**
     * Determine if the model can be activated.
     */
    public function canBeActivated();

    /**
     * Retrieve the model's first activation date.
     */
    public function getActivatedAtAttribute();

    /**
     * Check to see if the model is not in activation.
     */
    public function isNotInActivation();

    /**
     * Get the model's first employment date.
     *
     * @param  string  $activationDate
     */
    public function activatedOn(string $activationDate);
}
