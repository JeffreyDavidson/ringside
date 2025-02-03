<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Undocumented interface
 *
 * @template RelatedModel of Model
 */
interface Employable
{
    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function employments(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function currentEmployment(): HasOne;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function futureEmployment(): HasOne;

    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function previousEmployments(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function previousEmployment(): HasOne;

    /**
     * Undocumented function
     */
    public function hasEmployments(): bool;

    /**
     * Undocumented function
     */
    public function isCurrentlyEmployed(): bool;

    /**
     * Undocumented function
     */
    public function hasFutureEmployment(): bool;

    /**
     * Undocumented function
     */
    public function isNotInEmployment(): bool;

    /**
     * Undocumented function
     */
    public function isUnemployed(): bool;

    /**
     * Undocumented function
     */
    public function isReleased(): bool;

    /**
     * Undocumented function
     */
    public function employedOn(Carbon $employmentDate): bool;

    /**
     * Undocumented function
     */
    public function employedBefore(Carbon $employmentDate): bool;
}
