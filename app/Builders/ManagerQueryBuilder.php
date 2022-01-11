<?php

namespace App\Builders;

use App\Enums\ManagerStatus;

/**
 * The query builder attached to a manager.
 *
 * @template TModelClass of \App\Models\Manager
 * @extends SingleRosterMemberQueryBuilder<TModelClass>
 */
class ManagerQueryBuilder extends SingleRosterMemberQueryBuilder
{
    /**
     * Scope a query to only include available managers.
     *
     * @return \App\Builders\ManagerQueryBuilder
     */
    public function available()
    {
        return $this->where('status', ManagerStatus::available());
    }
}
