<?php

declare(strict_types=1);

namespace App\Models;

use Ankurk91\Eloquent\HasMorphToOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;

/**
 * @property-read \Illuminate\Support\Carbon $won_at
 */
class TitleChampionship extends Model
{
    /** @use HasFactory<\Database\Factories\TitleChampionshipFactory> */
    use HasFactory;

    use HasMergedRelationships;
    use HasMorphToOne;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'title_championships';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title_id',
        'event_match_id',
        'champion_id',
        'champion_type',
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
}
