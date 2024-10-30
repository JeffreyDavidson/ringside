<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\WrestlerStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends \Illuminate\Database\Eloquent\Builder<\App\Models\Wrestler>
 */
class WrestlerBuilder extends Builder
{
    public function unemployed(): static
    {
        $this->where('status', WrestlerStatus::Unemployed);

        return $this;
    }

    public function futureEmployed(): static
    {
        $this->where('status', WrestlerStatus::FutureEmployment);

        return $this;
    }

    public function employed(): static
    {
        $this->where('status', WrestlerStatus::Bookable);

        return $this;
    }

    /**
     * Scope a query to include bookable wrestlers.
     */
    public function bookable(): static
    {
        $this->where('status', WrestlerStatus::Bookable);

        return $this;
    }
}
