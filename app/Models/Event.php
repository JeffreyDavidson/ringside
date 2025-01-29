<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\EventBuilder;
use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read \Illuminate\Support\Carbon $date
 */
class Event extends Model
{
    /** @use HasBuilder<EventBuilder<static>> */
    use HasBuilder;

    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'date',
        'venue_id',
        'preview',
        'status',
    ];

    protected static string $builder = EventBuilder::class;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'status' => EventStatus::class,
        ];
    }

    /**
     * Retrieve the matches for the event.
     *
     * @return HasMany<EventMatch, $this>
     */
    public function matches(): HasMany
    {
        return $this->hasMany(EventMatch::class);
    }
}
