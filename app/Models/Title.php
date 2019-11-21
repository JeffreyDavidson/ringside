<?php

namespace App\Models;

use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use SoftDeletes,
        HasCachedAttributes,
        Concerns\CanBeRetired,
        Concerns\CanBeCompeted;

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
