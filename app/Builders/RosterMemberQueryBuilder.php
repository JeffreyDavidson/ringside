<?php

declare(strict_types=1);

namespace App\Builders;

use App\Builders\RosterMemberQueryBuilder;
use App\Models\Employment;
use App\Models\Retirement;
use App\Models\Suspension;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \App\Models\RosterMember
 *
 * @extends Builder<TModelClass>
 */
class RosterMemberQueryBuilder extends Builder
{
    /**
     * Scope a query to include suspended models.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function suspended(): RosterMemberQueryBuilder
    {
        return $this->whereHas('currentSuspension');
    }

    /**
     * Scope a query to include current suspension date.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function withCurrentSuspendedAtDate(): RosterMemberQueryBuilder
    {
        return $this->addSelect([
            'current_suspended_at' => Suspension::select('started_at')
                ->whereColumn('suspendable_id', $this->qualifyColumn('id'))
                ->where('suspendable_type', $this->getModel())
                ->latest('started_at')
                ->limit(1),
        ])->withCasts(['current_suspended_at' => 'datetime']);
    }

    /**
     * Scope a query to order by the model's current suspension date.
     *
     * @param  string  $direction
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function orderByCurrentSuspendedAtDate(string $direction = 'asc'): RosterMemberQueryBuilder
    {
        return $this->orderByRaw("DATE(current_suspended_at) {$direction}");
    }

    /**
     * Scope a query to only include retired models.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function retired(): RosterMemberQueryBuilder
    {
        return $this->whereHas('currentRetirement');
    }

    /**
     * Scope a query to include current retirement date.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function withCurrentRetiredAtDate(): RosterMemberQueryBuilder
    {
        return $this->addSelect([
            'current_retired_at' => Retirement::select('started_at')
                ->whereColumn('retiree_id', $this->getModel()->getTable().'.id')
                ->where('retiree_type', $this->getModel())
                ->latest('started_at')
                ->limit(1),
        ])->withCasts(['current_retired_at' => 'datetime']);
    }

    /**
     * Scope a query to order by the model's current retirement date.
     *
     * @param  string  $direction
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function orderByCurrentRetiredAtDate(string $direction = 'asc'): RosterMemberQueryBuilder
    {
        return $this->orderByRaw("DATE(current_retired_at) {$direction}");
    }

    /**
     * Scope a query to include released models.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function released(): RosterMemberQueryBuilder
    {
        return $this->whereHas('previousEmployment')
            ->whereDoesntHave('currentEmployment')
            ->whereDoesntHave('currentRetirement');
    }

    /**
     * Scope a query to include released date.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function withReleasedAtDate(): RosterMemberQueryBuilder
    {
        return $this->addSelect([
            'released_at' => Employment::select('ended_at')
                ->whereColumn('employable_id', $this->getModel()->getTable().'.id')
                ->where('employable_type', $this->getModel())
                ->latest('ended_at')
                ->limit(1),
        ])->withCasts(['released_at' => 'datetime']);
    }

    /**
     * Scope a query to order by the model's current released date.
     *
     * @param  string  $direction
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function orderByCurrentReleasedAtDate(string $direction = 'asc'): RosterMemberQueryBuilder
    {
        return $this->orderByRaw("DATE(current_released_at) {$direction}");
    }

    /**
     * Scope a query to include employed models.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function employed(): RosterMemberQueryBuilder
    {
        return $this->whereHas('currentEmployment');
    }

    /**
     * Scope a query to only include future employed models.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function futureEmployed(): RosterMemberQueryBuilder
    {
        return $this->whereHas('futureEmployment');
    }

    /**
     * Scope a query to include unemployed models.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function unemployed(): RosterMemberQueryBuilder
    {
        return $this->whereDoesntHave('employments');
    }

    /**
     * Scope a query to include first employment date.
     *
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function withFirstEmployedAtDate(): RosterMemberQueryBuilder
    {
        return $this->addSelect([
            'first_employed_at' => Employment::select('started_at')
                ->whereColumn('employable_id', $this->qualifyColumn('id'))
                ->where('employable_type', $this->getModel())
                ->oldest('started_at')
                ->limit(1),
        ])->withCasts(['first_employed_at' => 'datetime']);
    }

    /**
     * Scope a query to order by the model's first employment date.
     *
     * @param  string  $direction
     * @return \App\Builders\RosterMemberQueryBuilder
     */
    public function orderByFirstEmployedAtDate(string $direction = 'asc'): RosterMemberQueryBuilder
    {
        return $this->orderByRaw("DATE(first_employed_at) {$direction}");
    }
}
