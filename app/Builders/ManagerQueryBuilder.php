<?php

declare(strict_types=1);

namespace App\Builders;

use App\Builders\ManagerQueryBuilder;
use App\Enums\ManagerStatus;

/**
 * @template TModelClass of \App\Models\Manager
 *
 * @extends SingleRosterMemberQueryBuilder<\App\Models\Manager>
 */
class ManagerQueryBuilder extends SingleRosterMemberQueryBuilder
{
    /**
     * Scope a query to only include available managers.
     *
     * @return \App\Builders\ManagerQueryBuilder
     */
    public function available(): ManagerQueryBuilder
    {
        return $this->where('status', ManagerStatus::AVAILABLE);
    }
}
