<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\StableBuilder;
use App\Enums\ActivationStatus;
use App\Models\Contracts\Activatable;
use App\Models\Contracts\Retirable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property StableActivation $firstActivation
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property ActivationStatus $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StableActivation> $activations
 * @property-read \App\Models\StableActivation|null $currentActivation
 * @property-read \App\Models\StableWrestler|\App\Models\StableTagTeam|\App\Models\StableManager|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Manager> $currentManagers
 * @property-read \App\Models\StableRetirement|null $currentRetirement
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TagTeam> $currentTagTeams
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wrestler> $currentWrestlers
 * @property-read \App\Models\StableActivation|null $futureActivation
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Manager> $managers
 * @property-read \App\Models\StableActivation|null $previousActivation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StableActivation> $previousActivations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Manager> $previousManagers
 * @property-read \App\Models\StableRetirement|null $previousRetirement
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StableRetirement> $previousRetirements
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TagTeam> $previousTagTeams
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wrestler> $previousWrestlers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StableRetirement> $retirements
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TagTeam> $tagTeams
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wrestler> $wrestlers
 *
 * @method static StableBuilder<static>|Stable active()
 * @method static \Database\Factories\StableFactory factory($count = null, $state = [])
 * @method static StableBuilder<static>|Stable inactive()
 * @method static StableBuilder<static>|Stable newModelQuery()
 * @method static StableBuilder<static>|Stable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stable onlyTrashed()
 * @method static StableBuilder<static>|Stable query()
 * @method static StableBuilder<static>|Stable retired()
 * @method static StableBuilder<static>|Stable unactivated()
 * @method static StableBuilder<static>|Stable withFutureActivation()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stable withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stable withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Stable extends Model implements Activatable, Retirable
{
    use Concerns\HasMembers;
    use Concerns\IsActivatable;
    use Concerns\IsRetirable;
    use Concerns\OwnedByUser;

    /** @use HasBuilder<StableBuilder<static>> */
    use HasBuilder;

    /** @use HasFactory<\Database\Factories\StableFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * The minimum number of members allowed on a tag team.
     */
    public const int MIN_MEMBERS_COUNT = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'status',
    ];

    protected static string $builder = StableBuilder::class;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ActivationStatus::class,
        ];
    }

    /**
     * The model's default values for attributes.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'status' => ActivationStatus::Unactivated->value,
    ];

    /**
     * @return HasMany<StableActivation, $this>
     */
    public function activations(): HasMany
    {
        return $this->hasMany(StableActivation::class);
    }

    /**
     * @return HasMany<StableRetirement, $this>
     */
    public function retirements(): HasMany
    {
        return $this->hasMany(StableRetirement::class);
    }
}
