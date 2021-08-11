<?php

namespace App\Models;

use App\Enums\TagTeamStatus;
use App\Exceptions\CannotBeEmployedException;
use App\Exceptions\NotEnoughMembersException;
use App\Models\Contracts\Bookable;
use App\Models\Contracts\CanJoinStable;
use App\Models\Contracts\Employable;
use App\Models\Contracts\Reinstatable;
use App\Models\Contracts\Releasable;
use App\Models\Contracts\Retirable;
use App\Models\Contracts\Suspendable;
use App\Models\Contracts\Unretirable;
use Fidum\EloquentMorphToOne\HasMorphToOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagTeam extends Model implements Bookable, CanJoinStable, Employable, Releasable, Reinstatable, Retirable, Suspendable, Unretirable
{
    use SoftDeletes,
        HasFactory,
        HasMorphToOne,
        Concerns\CanJoinStable,
        Concerns\Retirable,
        Concerns\Suspendable,
        Concerns\Unguarded;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($tagTeam) {
            $tagTeam->updateStatus();
        });
    }

    /**
     * The number of the wrestlers allowed on a tag team.
     *
     * @var int
     */
    const MAX_WRESTLERS_COUNT = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tag_teams';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['currentWrestlers'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => TagTeamStatus::class,
    ];

    /**
     * Get the user belonging to the tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wrestlers that have been tag team partners of the tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wrestlers()
    {
        return $this->belongsToMany(Wrestler::class, 'tag_team_wrestler')
                    ->withPivot('joined_at', 'left_at');
    }

    /**
     * Get current tag team partners of the tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentWrestlers()
    {
        return $this->wrestlers()
                    ->wherePivot('joined_at', '<=', now())
                    ->wherePivot('left_at', '=', null)
                    ->limit(2);
    }

    /**
     * Get previous tag team partners of the tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function previousWrestlers()
    {
        return $this->wrestlers()
                    ->whereNotNull('left_at');
    }

    /**
     * Get the combined weight of both tag team partners in a tag team.
     *
     * @return int
     */
    public function getCombinedWeightAttribute()
    {
        return $this->currentWrestlers->sum('weight');
    }

    /**
     * Get all of the employments of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function employments()
    {
        return $this->morphMany(Employment::class, 'employable');
    }

    /**
     * Get the current employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function currentEmployment()
    {
        return $this->morphOne(Employment::class, 'employable')
                    ->where('started_at', '<=', now())
                    ->where('ended_at', '=', null)
                    ->latestOfMany();
    }

    /**
     * Get the future employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function futureEmployment()
    {
        return $this->morphOne(Employment::class, 'employable')
                    ->where('started_at', '>', now())
                    ->whereNull('ended_at')
                    ->latestOfMany();
    }

    /**
     * Get the previous employments of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousEmployments()
    {
        return $this->employments()
                    ->whereNotNull('ended_at');
    }

    /**
     * Get the previous employment of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function previousEmployment()
    {
        return $this->morphOne(Employment::class, 'employable')
                    ->whereNotNull('ended_at')
                    ->latest('ended_at')
                    ->latestOfMany();
    }

    /**
     * Determine if the tag team can be employed.
     *
     * @return bool
     */
    public function canBeEmployed()
    {
        if ($this->isCurrentlyEmployed()) {
            throw new CannotBeEmployedException;
        }

        if ($this->currentWrestlers->count() !== self::MAX_WRESTLERS_COUNT) {
            throw NotEnoughMembersException::forTagTeam();
        }

        return true;
    }

    /**
     * Scope a query to only include employed referees.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmployed($query)
    {
        return $query->whereHas('currentEmployment');
    }

    /**
     * Scope a query to only include future employed models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFutureEmployed($query)
    {
        return $query->whereHas('futureEmployment');
    }

    /**
     * Scope a query to only include released models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReleased($query)
    {
        return $query->whereHas('previousEmployment')
                    ->whereDoesntHave('currentEmployment')
                    ->whereDoesntHave('currentRetirement');
    }

    /**
     * Scope a query to only include unemployed models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnemployed($query)
    {
        return $query->whereDoesntHave('currentEmployment')
                    ->orWhereDoesntHave('previousEmployments');
    }

    /**
     * Scope a query to include first employment date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithFirstEmployedAtDate($query)
    {
        return $query->addSelect(['first_employed_at' => Employment::select('started_at')
            ->whereColumn('employable_id', $query->qualifyColumn('id'))
            ->where('employable_type', $this->getMorphClass())
            ->oldest('started_at')
            ->limit(1),
        ])->withCasts(['first_employed_at' => 'datetime']);
    }

    /**
     * Scope a query to order by the model's first employment date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByFirstEmployedAtDate($query, $direction = 'asc')
    {
        return $query->orderByRaw("DATE(first_employed_at) $direction");
    }

    /**
     * Scope a query to include released date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithReleasedAtDate($query)
    {
        return $query->addSelect(['released_at' => Employment::select('ended_at')
            ->whereColumn('employable_id', $this->getTable().'.id')
            ->where('employable_type', $this->getMorphClass())
            ->latest('ended_at')
            ->limit(1),
        ])->withCasts(['released_at' => 'datetime']);
    }

    /**
     * Scope a query to order by the model's current released date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByCurrentReleasedAtDate($query, $direction = 'asc')
    {
        return $query->orderByRaw("DATE(current_released_at) $direction");
    }

    /**
     * Check to see if the model is employed.
     *
     * @return bool
     */
    public function isCurrentlyEmployed()
    {
        return $this->currentEmployment()->exists();
    }

    /**
     * Check to see if the model has been employed.
     *
     * @return bool
     */
    public function hasEmployments()
    {
        return $this->employments()->count() > 0;
    }

    /**
     * Check to see if the model is not in employment.
     *
     * @return bool
     */
    public function isNotInEmployment()
    {
        return $this->isUnemployed() || $this->isReleased() || $this->hasFutureEmployment() || $this->isRetired();
    }

    /**
     * Check to see if the model is unemployed.
     *
     * @return bool
     */
    public function isUnemployed()
    {
        return $this->employments()->count() === 0;
    }

    /**
     * Check to see if the model has a future employment.
     *
     * @return bool
     */
    public function hasFutureEmployment()
    {
        return $this->futureEmployment()->exists();
    }

    /**
     * Check to see if the model has been released.
     *
     * @return bool
     */
    public function isReleased()
    {
        return $this->previousEmployment()->exists() &&
                $this->futureEmployment()->doesntExist() &&
                $this->currentEmployment()->doesntExist() &&
                $this->currentRetirement()->doesntExist();
    }

    /**
     * Determine if the tag team can be released.
     *
     * @return bool
     */
    public function canBeReleased()
    {
        if ($this->isNotInEmployment()) {
            throw new CannotBeEmployedException;
        }

        return true;
    }

    /**
     * Determine if the tag team can be reinstated.
     *
     * @return bool
     */
    public function canBeReinstated()
    {
        if (! $this->isSuspended()) {
            return false;
        }

        if ($this->currentWrestlers->count() != 2 || ! $this->currentWrestlers->each->canBeReinstated()) {
            return false;
        }

        return true;
    }

    /**
     * Scope a query to only include bookable tag teams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBookable($query)
    {
        return $query->where('status', 'bookable');
    }

    /**
     * Check to see if the tag team is bookable.
     *
     * @return bool
     */
    public function isBookable()
    {
        if ($this->isNotInEmployment()) {
            return false;
        }

        if (! $this->partnersAreBookable()) {
            return false;
        }

        return true;
    }

    /**
     * Check to see if the tag team is unbookable.
     *
     * @return bool
     */
    public function isUnbookable()
    {
        return ! $this->isBookable();
    }

    /**
     * Find out if both tag team partners are bookable.
     *
     * @return bool
     */
    public function partnersAreBookable()
    {
        foreach ($this->currentWrestlers as $wrestler) {
            if (! $wrestler->isBookable()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Update the status for the tag team.
     *
     * @return void
     */
    public function updateStatus()
    {
        if ($this->isCurrentlyEmployed()) {
            if ($this->isSuspended()) {
                $this->status = TagTeamStatus::SUSPENDED;
            } elseif ($this->isUnbookable()) {
                $this->status = TagTeamStatus::UNBOOKABLE;
            } elseif ($this->isBookable()) {
                $this->status = TagTeamStatus::BOOKABLE;
            }
        } elseif ($this->hasFutureEmployment()) {
            $this->status = TagTeamStatus::FUTURE_EMPLOYMENT;
        } elseif ($this->isReleased()) {
            $this->status = TagTeamStatus::RELEASED;
        } elseif ($this->isRetired()) {
            $this->status = TagTeamStatus::RETIRED;
        } else {
            $this->status = TagTeamStatus::UNEMPLOYED;
        }
    }

    /**
     * Updates a tag team's status and saves.
     *
     * @return void
     */
    public function updateStatusAndSave()
    {
        $this->updateStatus();
        $this->save();
    }
}
