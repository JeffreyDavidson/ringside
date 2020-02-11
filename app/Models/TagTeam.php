<?php

namespace App\Models;

use App\Eloquent\Concerns\HasCustomRelationships;
use App\Enums\TagTeamStatus;
use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MadWeb\Enum\EnumCastable;

class TagTeam extends Model
{
    use SoftDeletes,
        EnumCastable,
        HasCachedAttributes,
        HasCustomRelationships,
        Concerns\CanBeRetired,
        Concerns\CanBeSuspended,
        Concerns\CanBeEmployed,
        Concerns\CanBeBooked;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
     * Get the wrestlers belonging to the tag team.
     *
     * @return App\Eloquent\Relationships\LeaveableBelongsToMany
     */
    public function wrestlerHistory()
    {
        return $this->leaveableBelongsToMany(Wrestler::class, 'tag_team_wrestler', 'tag_team_id', 'wrestler_id');
    }

    /**
     * Get all current wrestlers that are members of the tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function currentWrestlers()
    {
        return $this->wrestlerHistory()->current();
    }

    /**
     * Get all current wrestlers that are members of the tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function previousWrestlers()
    {
        return $this->wrestlerHistory()->detached();
    }

    /**
     * Get the stables the tag team are members of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function stableHistory()
    {
        return $this->leaveableMorphToMany(Stable::class, 'member');
    }

    /**
     * Get the current stable of the tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentStable()
    {
        return $this->stableHistory()->current();
    }

    /**
     * Get the current stable of the tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function previousStables()
    {
        return $this->stableHistory()->detached();
    }

    /**
     * Get the combined weight of both wrestlers in a tag team.
     *
     * @return int
     */
    public function getCombinedWeightAttribute()
    {
        return $this->currentWrestlers->sum('weight');
    }

    /**
     * Add multiple wrestlers to a tag team.
     *
     * @param  array  $wrestlers
     * @return $this
     */
    public function addWrestlers($wrestlerIds)
    {
        $this->wrestlerHistory()->sync($wrestlerIds);

        return $this;
    }

    /**
     * Determine if the model can be reinstated.
     *
     * @return bool
     */
    public function canBeEmployed()
    {
        if ($this->isCurrentlyEmployed()) {
            return false;
        }

        if ($this->currentWrestlers->count() != 2) {
            return false;
        }

        return true;
    }

    /**
     * Employ a tag team.
     *
     * @return bool
     */
    public function employ($startAtDate = null)
    {
        $startAtDate = $startAtDate ?? now();
        $this->employments()->updateOrCreate(['ended_at' => null], ['started_at' => $startAtDate]);
        $this->wrestlerHistory->each->employ($startAtDate);

        return $this->touch();
    }

    /**
     * Determine if the model can be retired.
     *
     * @return bool
     */
    public function canBeRetired()
    {
        if (! $this->isCurrentlyEmployed()) {
            return false;
        }

        if ($this->isRetired()) {
            return false;
        }

        return true;
    }

    /**
     * Retire a tag team.
     *
     * @return \App\Models\Retirement
     */
    public function retire()
    {
        if ($this->is_suspended) {
            $this->reinstate();
        }

        $this->retirements()->create(['started_at' => now()]);

        $this->currentWrestlers->each->retire();

        $this->currentWrestlers->each->touch();

        return $this->touch();
    }

    /**
     * Unretire a tag team.
     *
     * @return bool
     */
    public function unretire()
    {
        $dateRetired = $this->currentRetirement->started_at;

        $this->currentRetirement()->update(['ended_at' => now()]);

        $this->wrestlerHistory()
            ->whereHas('currentRetirement', function ($query) use ($dateRetired) {
                $query->whereDate('started_at', $dateRetired);
            })
            ->get()
            ->each
            ->unretire();

        return $this->touch();
    }

    /**
     * Determine if the model can be reinstated.
     *
     * @return bool
     */
    public function canBeSuspended()
    {
        if (! $this->isCurrentlyEmployed()) {
            return false;
        }

        if ($this->isSuspended()) {
            return false;
        }

        return true;
    }

    /**
     * Suspend a tag team.
     *
     * @return \App\Models\Suspension
     */
    public function suspend()
    {
        $this->suspensions()->create(['started_at' => now()]);

        $this->currentWrestlers->each->suspend();

        return $this->touch();
    }

    /**
     * Determine if the model can be reinstated.
     *
     * @return bool
     */
    public function canBeReinstated()
    {
        if (! $this->isCurrentlyEmployed()) {
            return false;
        }

        if (! $this->isSuspended()) {
            return false;
        }

        return true;
    }

    /**
     * Reinstate a tag team.
     *
     * @return bool
     */
    public function reinstate()
    {
        $this->currentSuspension()->update(['ended_at' => now()]);

        return $this->touch();
    }

    /**
     * @return bool
     */
    public function isBookable()
    {
        if ($this->currentEmployment()->doesntExist()) {
            return false;
        }

        if ($this->currentSuspension()->exists()) {
            return false;
        }

        if ($this->currentRetirement()->exists()) {
            return false;
        }

        return true;
    }
}
