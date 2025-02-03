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
interface Activatable
{
    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function activations(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function currentActivation(): HasOne;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function futureActivation(): HasOne;

    /**
     * Undocumented function
     *
     * @return HasMany<RelatedModel, static>
     */
    public function previousActivations(): HasMany;

    /**
     * Undocumented function
     *
     * @return HasOne<RelatedModel, static>
     */
    public function previousActivation(): HasOne;

    /**
     * Undocumented function
     */
    public function hasActivations(): bool;

    /**
     * Undocumented function
     */
    public function isCurrentlyActivated(): bool;

    /**
     * Undocumented function
     */
    public function hasFutureActivation(): bool;

    /**
     * Undocumented function
     */
    public function isNotInActivation(): bool;

    /**
     * Undocumented function
     */
    public function isUnactivated(): bool;

    /**
     * Undocumented function
     */
    public function isDeactivated(): bool;

    /**
     * Undocumented function
     */
    public function activatedOn(Carbon $activationDate): bool;
}
