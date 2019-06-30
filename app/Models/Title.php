<?php

namespace App\Models;

use App\Traits\Retireable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
    use Retireable,
        SoftDeletes;

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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_active'];

    /**
     *
     */
    public function getIsActiveAttribute()
    {
        return is_null($this->retired_at) && !is_null($this->introduced_at) && $this->introduced_at->isPast();
    }

    /**
     * Scope a query to only include active titles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeActive($query)
    {
        $query->whereDoesntHave('retirements', function (Builder $query) {
            $query->whereNull('ended_at');
        });
        $query->whereNotNull('introduced_at');
        $query->where('introduced_at', '<=', now());
    }

    /**
     * Scope a query to only include inactive titles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeInactive($query)
    {
        $query->where('introduced_at', '>', now());
    }

    /**
     * Activate an inactive title.
     *
     * @return boolean
     */
    public function activate()
    {
        return $this->update(['introduced_at' => now()]);
    }
}
