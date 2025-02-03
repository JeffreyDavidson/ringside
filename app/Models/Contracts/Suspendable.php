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
interface Suspendable
{
    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function suspensions(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function currentSuspension(): HasOne;

    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function previousSuspensions(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function previousSuspension(): HasOne;

    /**
     * Undocumented function
     */
    public function isSuspended(): bool;

    /**
     * Undocumented function
     */
    public function hasSuspensions(): bool;
}
