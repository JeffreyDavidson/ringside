<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\TitleStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \App\Models\Title
 *
 * @extends \Illuminate\Database\Eloquent\Builder<TModelClass>
 */
class TitleBuilder extends Builder
{
    /**
     * Scope a query to include competable titles.
     */
    public function competable(): static
    {
        $this->where('status', TitleStatus::Active);

        return $this;
    }
}
