<?php

namespace App\Models;

use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Eloquent\Concerns\HasCustomRelationships;

/**
 * App\Models\Wrestler
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property int $height
 * @property int $weight
 * @property string $hometown
 * @property string|null $signature_move
 * @property \Illuminate\Support\Carbon $hired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $feet
 * @property-read string $formatted_height
 * @property-read string $formatted_hired_at
 * @property-read string $inches
 * @property-read bool $is_bookable
 * @property-read bool $is_hired
 * @property-read bool $is_injured
 * @property-read bool $is_retired
 * @property-read bool $is_suspended
 * @property-read \App\Enum\WrestlerStatus $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Injury[] $injuries
 * @property-read \App\Models\Injury $injury
 * @property-read \App\Models\Retirement $retirement
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Retirement[] $retirements
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Stable[] $stable
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Stable[] $stables
 * @property-read \App\Models\Suspension $suspension
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Suspension[] $suspensions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TagTeam[] $tagTeam
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TagTeam[] $tagTeams
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler bookable()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler pending_introduction()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler injured()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Wrestler onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler retired()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler suspended()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereHiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereHometown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereSignatureMove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wrestler whereWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Wrestler withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Wrestler withoutTrashed()
 * @mixin \Eloquent
 */
class Wrestler extends Model
{
    use SoftDeletes,
        HasCustomRelationships,
        HasCachedAttributes,
        Concerns\CanBeSuspended,
        Concerns\CanBeInjured,
        Concerns\CanBeRetired,
        Concerns\CanBeEmployed,
        Concerns\CanBeBooked,
        Concerns\HasAHeight;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user belonging to the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tag team history the wrestler has belonged to.
     *
     * @return App\Eloquent\Relationships\LeaveableBelongsToMany
     */
    public function tagTeamHistory()
    {
        return $this->leaveableBelongsToMany(TagTeam::class, 'tag_team_wrestler', 'wrestler_id', 'tag_team_id');
    }

    /**
     * Get the current tag team of the wrestler.
     *
     * @return App\Eloquent\Relationships\LeaveableBelongsToMany
     */
    public function currentTagTeam()
    {
        return $this->tagTeamHistory()->where('status', 'bookable')->current();
    }

    /**
     * Get the previous tag teams the wrestler has belonged to.
     *
     * @return App\Eloquent\Relationships\LeaveableBelongsToMany
     */
    public function previousTagTeams()
    {
        return $this->tagTeamHistory()->detached();
    }

    /**
     * Get the stable history the wrestler has belonged to.
     *
     * @return App\Eloquent\Relationships\LeaveableMorphToMany
     */
    public function stableHistory()
    {
        return $this->leaveableMorphToMany(Stable::class, 'member')->using(Member::class);
    }

    /**
     * Get the current stable the wrestler belongs to.
     *
     * @return App\Eloquent\Relationships\LeaveableMorphToMany
     */
    public function currentStable()
    {
        return $this->stableHistory()->where('status', 'bookable')->current();
    }

    /**
     * Get the previous stables the wrestler has belonged to.
     *
     * @return App\Eloquent\Relationships\LeaveableMorphToMany
     */
    public function previousStables()
    {
        return $this->stableHistory()->detached();
    }
}
