<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class TagTeamBuilder extends Builder
{
    use Concerns\HasEmployments;
    use Concerns\HasRetirements;
    use Concerns\HasSuspensions;

    /**
     * Scope a query to include bookable tag teams.
     */
    public function bookable(): self
    {
        $this->where('status', 'bookable');

        return $this;
    }

    /**
     * Scope a query to include bookable tag teams.
     */
    public function unbookable(): self
    {
        $this->where('status', 'unbookable');

        return $this;
    }
}
