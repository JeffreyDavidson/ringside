<?php

namespace App\Models;

use App\Enums\TagTeamStatus;
use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Eloquent\Concerns\HasCustomRelationships;

class TagTeam extends Model
{
    use SoftDeletes,
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
     * @return integer
     */
    public function getCombinedWeightAttribute()
    {
        return $this->wrestlers->sum('weight');
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
     * Employ a tag team.
     *
     * @return bool
     */
    public function employ()
    {
        $this->employments()->latest()->first()->update(['started_at' => now()]);

        $this->wrestlers->each->employ();

        return $this;
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

        $this->wrestlers->each->retire();

        return $this;
    }

    /**
     * Unretire a tag team.
     *
     * @return bool
     */
    public function unretire()
    {
        $this->retirement()->update(['ended_at' => now()]);

        $this->wrestlers->filter->retired()->each->unretire();
    }

    /**
     * Suspend a tag team.
     *
     * @return \App\Models\Suspension
     */
    public function suspend()
    {
        $this->suspensions()->create(['started_at' => now()]);

        $this->wrestlers->each->suspend();
    }

    /**
     * Reinstate a tag team.
     *
     * @return bool
     */
    public function reinstate()
    {
        $this->suspension()->update(['ended_at' => now()]);
    }
}
