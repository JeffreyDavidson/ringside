<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Undocumented interface
 */
interface Bookable
{
    /**
     * Undocumented function
     *
     * @return MorphMany<\Illuminate\Database\Eloquent\Model, \Illuminate\Database\Eloquent\Model>
     */
    public function matches(): MorphToMany;

    /**
     * Undocumented function
     */
    public function isBookable(): bool;
}
