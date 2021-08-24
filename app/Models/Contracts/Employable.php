<?php

namespace App\Models\Contracts;

interface Employable
{
    /**
     * Get all of the employments of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function employments();

    /**
     * Get the first employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function firstEmployment();

    /**
     * Get the current employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function currentEmployment();

    /**
     * Get the future employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function futureEmployment();

    /**
     * Get the previous employments of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousEmployments();

    /**
     * Get the previous employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function previousEmployment();

    /**
     * Scope a query to include employed models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmployed($query);

    /**
     * Scope a query to only include future employed models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFutureEmployed($query);

    /**
     * Scope a query to include unemployed models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnemployed($query);

    /**
     * Scope a query to include first employment date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithFirstEmployedAtDate($query);

    /**
     * Scope a query to order by the model's first employment date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByFirstEmployedAtDate($query, $direction = 'asc');

    /**
     * Check to see if the model is employed.
     *
     * @return bool
     */
    public function isCurrentlyEmployed();

    /**
     * Check to see if the model has been employed.
     *
     * @return bool
     */
    public function hasEmployments();

    /**
     * Check to see if the model is not in employment.
     *
     * @return bool
     */
    public function isNotInEmployment();

    /**
     * Check to see if the model is unemployed.
     *
     * @return bool
     */
    public function isUnemployed();

    /**
     * Check to see if the model has a future employment.
     *
     * @return bool
     */
    public function hasFutureEmployment();

    /**
     * Determine if the model can be employed.
     *
     * @return bool
     */
    public function canBeEmployed();

    /**
     * Get the model's first employment date.
     *
     * @return string|null
     */
    public function getStartedAtAttribute();

    /**
     * Get the model's first employment date.
     *
     * @param  string $employmentDate
     * @return bool
     */
    public function employedOn(string $employmentDate);
}
