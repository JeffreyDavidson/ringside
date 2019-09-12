<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Eloquent\Concerns\HasCustomRelationships;

class Stable extends Model
{
    use SoftDeletes, 
        HasCustomRelationships;

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
    public function wrestlerHistory()
    {
        return $this->leaveableMorphedByMany(Wrestler::class, 'member')->using(Member::class);
    }

    /**
     * Get all current wrestlers that are members of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function currentWrestlers()
    {
        return $this->wrestlerHistory()->current();
    }

    /**
     * Get all previous wrestlers that were members of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function previousWrestlers()
    {
        return $this->wrestlerHistory()->detached();
    }

    /**
     * Get all tag teams that have been members of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function tagTeamHistory()
    {
        return $this->leaveableMorphedByMany(TagTeam::class, 'member')->using(Member::class);
    }

    /**
     * Get all current tag teams that are members of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function currentTagTeams()
    {
        return $this->tagTeamHistory()->current();
    }

    /**
     * Get all previous tag teams that were members of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function previousTagTeams()
    {
        return $this->tagTeamHistory()->detached();
    }

    /**
     * Get all the current members of the stable.
     *
     * @return Collection
     */
    public function getCurrentMembersAttribute()
    {
        return $this->currentWrestlers->merge($this->currentTagTeams);
    }

    /**
     * Get all the previous members of the stable.
     *
     * @return Collection
     */
    public function getPreviousMembersAttribute()
    {
        return $this->previousWrestlers->merge($this->previousTagTeams);
    }

    /**
     * Add wrestlers to the stable.
     *
     * @param  array  $wrestlerIds
     * @return $this
     */
    public function addWrestlers($wrestlerIds)
    {
        $this->wrestlerHistory()->sync($wrestlerIds);

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
        $this->tagTeamHistory()->sync($tagteamIds);

        return $this;
    }

    /**
     * 
     */
    public function disassemble()
    {
        $this->currentWrestlers()->detach();
        $this->currentTagteams()->detach();
        $this->touch();

        return $this;
    }
}
