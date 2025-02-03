<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\TitleBuilder;
use App\Enums\TitleStatus;
use App\Models\Contracts\Activatable;
use App\Models\Contracts\Retirable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property TitleActivation $firstActivation
 * @property int $id
 * @property string $name
 * @property TitleStatus $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TitleActivation> $activations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TitleChampionship> $championships
 * @property-read \App\Models\TitleActivation|null $currentActivation
 * @property-read \App\Models\TitleChampionship|null $currentChampionship
 * @property-read \App\Models\TitleRetirement|null $currentRetirement
 * @property-read \App\Models\TitleActivation|null $futureActivation
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\TitleActivation|null $previousActivation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TitleActivation> $previousActivations
 * @property-read \App\Models\TitleRetirement|null $previousRetirement
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TitleRetirement> $previousRetirements
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TitleRetirement> $retirements
 * @method static TitleBuilder<static>|Title active()
 * @method static TitleBuilder<static>|Title competable()
 * @method static \Database\Factories\TitleFactory factory($count = null, $state = [])
 * @method static TitleBuilder<static>|Title inactive()
 * @method static TitleBuilder<static>|Title newModelQuery()
 * @method static TitleBuilder<static>|Title newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Title onlyTrashed()
 * @method static TitleBuilder<static>|Title query()
 * @method static TitleBuilder<static>|Title retired()
 * @method static TitleBuilder<static>|Title withFutureActivation()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Title withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Title withoutTrashed()
 * @mixin \Eloquent
 */
class Title extends Model implements Activatable, Retirable
{
    use Concerns\HasChampionships;

    /** @use HasBuilder<TitleBuilder<static>> */
    use HasBuilder;

    /** @use HasFactory<\Database\Factories\TitleFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'status' => TitleStatus::Unactivated->value,
    ];

    protected static string $builder = TitleBuilder::class;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TitleStatus::class,
        ];
    }

    /**
     * @return HasMany<TitleActivation, $this>
     */
    public function activations(): HasMany
    {
        return $this->hasMany(TitleActivation::class);
    }

    /**
     * @return HasOne<TitleActivation, $this>
     */
    public function currentActivation(): HasOne
    {
        return $this->activations()
            ->whereNull('ended_at')
            ->one();
    }

    /**
     * @return HasOne<TitleActivation, $this>
     */
    public function futureActivation(): HasOne
    {
        return $this->activations()
            ->whereNull('ended_at')
            ->where('started_at', '>', now())
            ->one();
    }

    /**
     * @return HasMany<TitleActivation, $this>
     */
    public function previousActivations(): HasMany
    {
        return $this->activations()
            ->whereNotNull('ended_at');
    }

    /**
     * @return HasOne<TitleActivation, $this>
     */
    public function previousActivation(): HasOne
    {
        return $this->previousActivations()
            ->one()
            ->ofMany('ended_at', 'max');
    }

    /**
     * @return HasOne<TitleActivation, $this>
     */
    public function firstActivation(): HasOne
    {
        return $this->activations()
            ->one()
            ->ofMany('started_at', 'min');
    }

    public function hasActivations(): bool
    {
        return $this->activations()->count() > 0;
    }

    public function isCurrentlyActivated(): bool
    {
        return $this->currentActivation()->exists();
    }

    public function hasFutureActivation(): bool
    {
        return $this->futureActivation()->exists();
    }

    public function isNotInActivation(): bool
    {
        return $this->isDeactivated() || $this->hasFutureActivation() || $this->isRetired();
    }

    public function isUnactivated(): bool
    {
        return $this->activations()->count() === 0;
    }

    public function isDeactivated(): bool
    {
        return $this->previousActivation()->exists()
            && $this->futureActivation()->doesntExist()
            && $this->currentActivation()->doesntExist()
            && $this->currentRetirement()->doesntExist();
    }

    public function activatedOn(Carbon $activationDate): bool
    {
        return $this->currentActivation ? $this->currentActivation->started_at->eq($activationDate) : false;
    }

    /**
     * @return HasMany<TitleRetirement, $this>
     */
    public function retirements(): HasMany
    {
        return $this->hasMany(TitleRetirement::class);
    }

    /**
     * @return HasOne<TitleRetirement, $this>
     */
    public function currentRetirement(): HasOne
    {
        return $this->retirements()
            ->whereNull('ended_at')
            ->one();
    }

    /**
     * @return HasMany<TitleRetirement, $this>
     */
    public function previousRetirements(): HasMany
    {
        return $this->retirements()
            ->whereNotNull('ended_at');
    }

    /**
     * @return HasOne<TitleRetirement, $this>
     */
    public function previousRetirement(): HasOne
    {
        return $this->previousRetirements()
            ->one()
            ->ofMany('ended_at', 'max');
    }

    public function isRetired(): bool
    {
        return $this->currentRetirement()->exists();
    }

    public function hasRetirements(): bool
    {
        return $this->retirements()->count() > 0;
    }
}
