<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\StableQueryBuilder;
use App\Enums\StableStatus;
use App\Models\Concerns\Activations;
use App\Models\Concerns\Deactivations;
use App\Models\Concerns\HasMembers;
use App\Models\Concerns\OwnedByUser;
use App\Models\Contracts\Activatable;
use App\Models\Contracts\Deactivatable;
use App\Models\Contracts\Retirable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stable extends Model implements Activatable, Deactivatable, Retirable
{
    use Activations;
    use Deactivations;
    use HasFactory;
    use HasMembers;
    use OwnedByUser;
    use SoftDeletes;

    /**
     * The minium number of members allowed on a tag team.
     */
    public const MIN_MEMBERS_COUNT = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'name', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => StableStatus::class,
    ];

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \App\Builders\StableQueryBuilder<Stable>
     */
    public function newEloquentBuilder($query)
    {
        return new StableQueryBuilder($query);
    }

    /**
     * Get the retirements of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retiree');
    }

    /**
     * Get the current retirement of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function currentRetirement()
    {
        return $this->morphOne(Retirement::class, 'retiree')
            ->where('started_at', '<=', now())
            ->whereNull('ended_at')
            ->limit(1);
    }

    /**
     * Get the previous retirements of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function previousRetirements()
    {
        return $this->morphMany(Retirement::class, 'retiree')
            ->whereNotNull('ended_at');
    }

    /**
     * Get the previous retirement of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function previousRetirement()
    {
        return $this->morphOne(Retirement::class, 'retiree')
            ->latest('ended_at')
            ->limit(1);
    }

    /**
     * Check to see if the model is retired.
     *
     * @return bool
     */
    public function isRetired()
    {
        return $this->currentRetirement()->exists();
    }

    /**
     * Check to see if the model has been activated.
     *
     * @return bool
     */
    public function hasRetirements()
    {
        return $this->retirements()->count() > 0;
    }
}
