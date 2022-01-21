<?php

namespace App\Models\Concerns;

use App\Models\TagTeam;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

trait CanJoinTagTeams
{
    use HasRelationships;

    /**
     * Get the tag teams the model has been belonged to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tagTeams()
    {
        return $this->belongsToMany(TagTeam::class, 'tag_team_wrestler')
            ->withPivot(['joined_at', 'left_at']);
    }

    /**
     * Get the current tag team the member belongs to.
     *
     * @return \Staudenmeir\EloquentHasManyDeep\HasOneDeep
     */
    public function currentTagTeam(): HasOneDeep
    {
        return $this->hasOneDeep(TagTeam::class, ['tag_team_wrestler'])
            ->whereNull('left_at');
    }

    /**
     * Get the previous tag teams the member has belonged to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function previousTagTeams()
    {
        return $this->belongsToMany(TagTeam::class, 'tag_team_wrestler')
            ->withPivot(['joined_at', 'left_at'])
            ->whereNotNull('ended_at');
    }
}
