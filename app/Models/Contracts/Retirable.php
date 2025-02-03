<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Undocumented interface
 *
 * @template RelatedModel of Model
 */
interface Retirable
{
    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function retirements(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function currentRetirement(): HasOne;

    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function previousRetirements(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function previousRetirement(): HasOne;

    /**
     * Undocumented function
     */
    public function isRetired(): bool;

    /**
     * Undocumented function
     */
    public function hasRetirements(): bool;
}
