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
interface Injurable
{
    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function injuries(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function currentInjury(): HasOne;

    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function previousInjuries(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function previousInjury(): HasOne;

    /**
     * Undocumented function
     */
    public function isInjured(): bool;

    /**
     * Undocumented function
     */
    public function hasInjuries(): bool;
}
