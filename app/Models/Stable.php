<?php

namespace App\Models;

use App\Enums\StableStatus;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stable extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
     * Get all wrestlers that have been members of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function wrestlers()
    {
        return $this->morphedByMany(Wrestler::class, 'member')->withPivot('left_at');
    }

    /**
     * Get all tag teams that have been members of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function tagteams()
    {
        return $this->morphedByMany(TagTeam::class, 'member')->withPivot('left_at');
    }

    /**
     * Get the retirements of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retiree');
    }

    /**
     * Get the current retirement of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function retirement()
    {
        return $this->morphOne(Retirement::class, 'retiree')->whereNull('ended_at');
    }

    /**
     * Get all of the employments of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function employments()
    {
        return $this->morphMany(Employment::class, 'employable')->whereNull('ended_at');
    }

    /**
     * Get the current employment of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function employment()
    {
        return $this->morphOne(Employment::class, 'employable')->whereNull('ended_at');
    }

    /**
     * Get all the members of the stable.
     *
     * @return Collection
     */
    public function getMembersAttribute()
    {
        return $this->wrestlers->merge($this->tagteams);
    }

    /**
     * Determine the status of the stable.
     *
     * @return \App\Enum\WrestlerStatus
     *
     */
    public function getStatusAttribute()
    {
        if ($this->is_bookable) {
            return StableStatus::BOOKABLE();
        }

        if ($this->is_retired) {
            return StableStatus::RETIRED();
        }

        return StableStatus::PENDING_INTRODUCTION();
    }

    /**
     * Scope a query to only include bookable tag teams.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeBookable($query)
    {
        return $query->whereHas('employments', function (Builder $query) {
            $query->where('started_at', '<=', now())->whereNull('ended_at');
        })->whereDoesntHave('retirements', function (Builder $query) {
            $query->whereNull('ended_at');
        });
    }

    /**
     * Scope a query to only include pending introduction stables.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePendingIntroduction($query)
    {
        return $query->whereHas('employments', function (Builder $query) {
            $query->whereNull('started_at')->orWhere('started_at', '>', now());
        });
    }

    /**
     * Scope a query to only include retired stables.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRetired($query)
    {
        return $query->whereHas('retirements', function ($query) {
            $query->whereNull('ended_at');
        });
    }

    /**
     * Add wrestlers to the stable.
     *
     * @param  array  $wrestlerIds
     * @return $this
     */
    public function addWrestlers($wrestlerIds)
    {
        foreach ($wrestlerIds as $wrestlerId) {
            $this->wrestlers()->attach($wrestlerId);
        }

        return $this;
    }

    /**
     * Add tag teams to the stable.
     *
     * @param  array  $tagteamIds
     * @return $this
     */
    public function addTagTeams($tagteamIds)
    {
        foreach ($tagteamIds as $tagteamId) {
            $this->tagteams()->attach($tagteamId);
        }

        return $this;
    }

    /**
     * Retire a stable.
     *
     * @return void
     */
    public function retire()
    {
        $this->retirements()->create(['started_at' => now()]);

        $this->wrestlers->each->retire();
        $this->tagteams->each->retire();

        return $this;
    }

    /**
     * Unretire a stable.
     *
     * @return void
     */
    public function unretire()
    {
        $this->unretireStable();

        $this->wrestlers->filter->is_retired->each->unretire();
        $this->tagteams->filter->is_retired->each->unretire();

        return $this;
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $data                 = parent::toArray();
        $data['status']       = $this->status->label();

        return $data;
    }
}
