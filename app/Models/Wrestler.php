<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\WrestlerBuilder;
use App\Casts\HeightCast;
use App\Enums\WrestlerStatus;
use App\Models\Contracts\Bookable;
use App\Models\Contracts\CanBeAStableMember;
use App\Models\Contracts\Employable;
use App\Models\Contracts\Injurable;
use App\Models\Contracts\Manageable;
use App\Models\Contracts\Retirable;
use App\Models\Contracts\Suspendable;
use App\Models\Contracts\TagTeamMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Wrestler extends Model implements Bookable, CanBeAStableMember, Employable, Injurable, Manageable, Retirable, Suspendable, TagTeamMember
{
    use Concerns\CanJoinStables;
    use Concerns\CanJoinTagTeams;
    use Concerns\CanWinTitles;
    use Concerns\HasManagers;
    use Concerns\HasMatches;
    use Concerns\OwnedByUser;

    /** @use HasBuilder<WrestlerBuilder<static>> */
    use HasBuilder;

    /** @use HasFactory<\Database\Factories\WrestlerFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'height',
        'weight',
        'hometown',
        'signature_move',
        'status',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'status' => WrestlerStatus::Unemployed->value,
    ];

    protected static string $builder = WrestlerBuilder::class;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'height' => HeightCast::class,
            'status' => WrestlerStatus::class,
        ];
    }

    /**
     * Get all the employments of the model.
     *
     * @return HasMany<WrestlerEmployment>
     */
    public function employments(): HasMany
    {
        return $this->hasMany(WrestlerEmployment::class);
    }

    /**
     * @return HasOne<WrestlerEmployment>
     */
    public function currentEmployment(): HasOne
    {
        return $this->employments()
            ->whereNull('ended_at')
            ->one();
    }

    /**
     * @return HasOne<WrestlerEmployment>
     */
    public function futureEmployment(): HasOne
    {
        return $this->employments()
            ->whereNull('ended_at')
            ->where('started_at', '>', now())
            ->one();
    }

    /**
     * @return HasMany<WrestlerEmployment>
     */
    public function previousEmployments(): HasMany
    {
        return $this->employments()
            ->whereNotNull('ended_at');
    }

    /**
     * @return HasOne<WrestlerEmployment>
     */
    public function previousEmployment(): HasOne
    {
        return $this->previousEmployments()
            ->one()
            ->ofMany('ended_at', 'max');
    }

    public function hasEmployments(): bool
    {
        return $this->employments()->count() > 0;
    }

    public function isCurrentlyEmployed(): bool
    {
        return $this->currentEmployment()->exists();
    }

    public function hasFutureEmployment(): bool
    {
        return $this->futureEmployment()->exists();
    }

    public function isNotInEmployment(): bool
    {
        return $this->isUnemployed() || $this->isReleased() || $this->isRetired();
    }

    public function isUnemployed(): bool
    {
        return $this->employments()->count() === 0;
    }

    public function isReleased(): bool
    {
        return $this->previousEmployment()->exists()
            && $this->futureEmployment()->doesntExist()
            && $this->currentEmployment()->doesntExist()
            && $this->currentRetirement()->doesntExist();
    }

    public function employedOn(Carbon $employmentDate): bool
    {
        return $this->currentEmployment ? $this->currentEmployment->started_at->eq($employmentDate) : false;
    }

    public function employedBefore(Carbon $employmentDate): bool
    {
        return $this->currentEmployment ? $this->currentEmployment->started_at->lte($employmentDate) : false;
    }

    /**
     * @return HasMany<WrestlerRetirement>
     */
    public function retirements(): HasMany
    {
        return $this->hasMany(WrestlerRetirement::class);
    }

    /**
     * @return HasOne<WrestlerRetirement>
     */
    public function currentRetirement(): HasOne
    {
        return $this->retirements()
            ->whereNull('ended_at')
            ->one();
    }

    /**
     * @return HasMany<WrestlerRetirement>
     */
    public function previousRetirements(): HasMany
    {
        return $this->retirements()
            ->whereNotNull('ended_at');
    }

    /**
     * @return HasOne<WrestlerRetirement>
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

    /**
     * @return HasMany<WrestlerInjury>
     */
    public function injuries(): HasMany
    {
        return $this->hasMany(WrestlerInjury::class);
    }

    /**
     * @return HasOne<WrestlerInjury>
     */
    public function currentInjury(): HasOne
    {
        return $this->injuries()
            ->whereNull('ended_at')
            ->one();
    }

    /**
     * @return HasMany<WrestlerInjury>
     */
    public function previousInjuries(): HasMany
    {
        return $this->injuries()
            ->whereNotNull('ended_at');
    }

    /**
     * @return HasOne<WrestlerInjury>
     */
    public function previousInjury(): HasOne
    {
        return $this->previousInjuries()
            ->one()
            ->ofMany('ended_at', 'max');
    }

    public function isInjured(): bool
    {
        return $this->currentInjury()->exists();
    }

    public function hasInjuries(): bool
    {
        return $this->injuries()->count() > 0;
    }

    /**
     * @return HasMany<WrestlerSuspension>
     */
    public function suspensions(): HasMany
    {
        return $this->hasMany(WrestlerSuspension::class);
    }

    /**
     * @return HasOne<WrestlerSuspension>
     */
    public function currentSuspension(): HasOne
    {
        return $this->suspensions()
            ->whereNull('ended_at')
            ->one();
    }

    /**
     * @return HasMany<WrestlerSuspension>
     */
    public function previousSuspensions(): HasMany
    {
        return $this->suspensions()
            ->whereNotNull('ended_at');
    }

    /**
     * @return HasOne<WrestlerSuspension>
     */
    public function previousSuspension(): HasOne
    {
        return $this->suspensions()
            ->one()
            ->ofMany('ended_at', 'max');
    }

    public function isSuspended(): bool
    {
        return $this->currentSuspension()->exists();
    }

    public function hasSuspensions(): bool
    {
        return $this->suspensions()->count() > 0;
    }

    /**
     * Retrieve the championships held by the model.
     *
     * @return BelongsToMany<Title>
     */
    public function titleChampionships(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, 'wrestlers_title_championships')
            ->withPivot('won_at', 'lost_at')
            ->using(WrestlerTitleChampionship::class);
    }

    /**
     * Retrieve the former championships held by the model.
     *
     * @return BelongsToMany<Title>
     */
    public function previousTitleChampionships(): BelongsToMany
    {
        return $this->belongsToMany(TitleChampionship::class, 'wrestlers_title_championships', 'former_champion_id')
            ->withPivot('won_at', 'lost_at')
            ->whereNotNull('lost_at')
            ->using(WrestlerTitleChampionship::class);
    }

    /**
     * Retrieve the current championships held by the model.
     *
     * @return BelongsToMany<Title>
     */
    public function currentTitleChampionship(): BelongsToMany
    {
        return $this->belongsToMany(TitleChampionship::class, 'wrestlers_title_championships', 'new_champion_id')
            ->withPivot('won_at', 'lost_at')
            ->whereNull('lost_at')
            ->using(WrestlerTitleChampionship::class);
    }
}
