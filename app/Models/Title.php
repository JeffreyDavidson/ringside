<?php

namespace App\Models;

use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * \App\Models\Title
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon $introduced_at
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Retirement $previousRetirement
 * @property-read \App\Models\Retirement $retirement
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Retirement[] $retirements
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Title onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title retired()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title whereIntroducedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Title whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Title withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Title withoutTrashed()
 * @mixin \Eloquent
 */
class Title extends Model
{
    use SoftDeletes,
        HasCachedAttributes,
        Concerns\CanBeRetired,
        Concerns\CanBeBooked;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['introduced_at'];

    /**
     * Determine if a title is has been introduced.
     *
     * @return bool
     */
    public function getIsPendingIntroductionAttribute()
    {
        return is_null($this->introduced_at) || $this->introduced_at->isFuture();
    }

    /**
     * Determine if a title is usuable.
     *
     * @return bool
     */
    public function getIsBookableAttribute()
    {
        return !($this->is_retired || $this->is_pending_introduction);
    }

    /**
     * Retrieve a formatted introduced at date timestamp.
     *
     * @return string
     */
    public function getFormattedIntroducedAtAttribute()
    {
        return $this->introduced_at->toDateString();
    }

    /**
     * Scope a query to only include active titles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeBookable($query)
    {
        $query->whereDoesntHave('retirements', function (Builder $query) {
            $query->whereNull('ended_at');
        });
        $query->whereNotNull('introduced_at');
        $query->where('introduced_at', '<=', now());
    }

    /**
     * Scope a query to only include pending introduced titles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePendingIntroduction($query)
    {
        $query->where('introduced_at', '>', now());
    }

    /**
     * Activate a title.
     *
     * @return \App\Models\Title $this
     */
    public function activate()
    {
        $this->update(['introduced_at' => now()]);

        return $this;
    }
}
