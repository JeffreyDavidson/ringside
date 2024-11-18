<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\TagTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \Illuminate\Support\Carbon $won_at
 */
class TagTeamTitleChampionship extends Model
{
    /** @use HasFactory<\Database\Factories\TagTeamTitleChampionshipFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tag_teams_title_championships';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_id',
        'event_match_id',
        'new_champion_id',
        'former_champion_id',
        'won_at',
        'lost_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'won_at' => 'datetime',
            'lost_at' => 'datetime',
            'last_held_reign' => 'datetime',
        ];
    }

    /**
     * Retrieve the title of the championship.
     *
     * @return BelongsTo<Title, TitleChampionship>
     */
    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    /**
     * Retrieve the current champion of the title championship.
     *
     * @return BelongsTo<TagTeam, TagTeamTitleChampionship>
     */
    public function currentChampion(): BelongsTo
    {
        return $this->belongsTo(TagTeam::class, 'new_champion_id');
    }

    /**
     * Retrieve the former champion of the title championship.
     *
     * @return BelongsTo<TagTeam, TagTeamTitleChampionship>
     */
    public function formerChampion(): BelongsTo
    {
        return $this->belongsTo(TagTeam::class, 'former_champion_id');
    }

    /**
     * Retrieve the event match where the title championship switched hands.
     *
     * @return BelongsTo<EventMatch, TitleChampionship>
     */
    public function eventMatch(): BelongsTo
    {
        return $this->belongsTo(EventMatch::class);
    }

    /**
     * Retrieve the number of days for a title championship.
     */
    public function lengthInDays(): int
    {
        $datetime = $this->lost_at ?? now();

        return intval($this->won_at->diffInDays($datetime));
    }
}
