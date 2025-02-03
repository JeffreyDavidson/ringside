<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $left_at
 * @property int $id
 * @property int $tag_team_id
 * @property int $wrestler_id
 * @property Carbon|null $joined_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\TagTeam|null $tagTeam
 * @property-read \App\Models\Wrestler|null $wrestler
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TagTeamPartner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TagTeamPartner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TagTeamPartner query()
 *
 * @mixin \Eloquent
 */
class TagTeamPartner extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tag_teams_wrestlers';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'left_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<TagTeam, $this>
     */
    public function tagTeam(): BelongsTo
    {
        return $this->belongsTo(TagTeam::class);
    }

    /**
     * @return BelongsTo<Wrestler, $this>
     */
    public function wrestler(): BelongsTo
    {
        return $this->belongsTo(Wrestler::class);
    }
}
