<?php

namespace App\Models;

use App\Enums\WrestlerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wrestler extends Model
{
    use SoftDeletes;

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
    protected $dates = ['hired_at'];

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
     * Get the tag teams the wrestler has belonged to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tagteams()
    {
        return $this->belongsToMany(TagTeam::class);
    }

    /**
     * Get the current tag team of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tagteam()
    {
        return $this->belongsToMany(TagTeam::class)->where('is_active', true);
    }

    /**
     * Get the stables the wrestler is a member of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function stables()
    {
        return $this->morphToMany(Stable::class, 'member');
    }

    /**
     * Get the current stable of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stable()
    {
        return $this->morphToMany(Stable::class, 'member')->where('is_active', true);
    }

    /**
     * Get the retirements of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retiree');
    }

    /**
     * Get the current retirement of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function retirement()
    {
        return $this->morphOne(Retirement::class, 'retiree')->whereNull('ended_at');
    }

    /**
     * Get the suspensions of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function suspensions()
    {
        return $this->morphMany(Suspension::class, 'suspendable');
    }

    /**
     * Get the current suspension of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function suspension()
    {
        return $this->morphOne(Suspension::class, 'suspendable')->whereNull('ended_at');
    }

    /**
     * Get the injuries of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function injuries()
    {
        return $this->morphMany(Injury::class, 'injurable');
    }

    /**
     * Get the current injury of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function injury()
    {
        return $this->morphOne(Injury::class, 'injurable')->whereNull('ended_at');
    }

    /**
     * Return the wrestler's height formatted.
     *
     * @return string
     */
    public function getFormattedHeightAttribute()
    {
        $feet = floor($this->height / 12);
        $inches = ($this->height % 12);

        return $feet . '\'' . $inches . '"';
    }

    /**
     * Return the wrestler's hired at date formatted.
     *
     * @return string
     */
    public function getFormattedHiredAtAttribute()
    {
        return $this->hired_at->format('M d, Y');
    }

    /**
     * Determine the status of the wrestler.
     *
     * @return \App\Enum\WrestlerStatus
     *
     */
    public function getStatusAttribute()
    {
        if ($this->is_bookable) {
            return WrestlerStatus::BOOKABLE();
        }

        if ($this->is_retired) {
            return WrestlerStatus::RETIRED();
        }

        if ($this->is_injured) {
            return WrestlerStatus::INJURED();
        }

        if ($this->is_suspended) {
            return WrestlerStatus::SUSPENDED();
        }

        return WrestlerStatus::INACTIVE();
    }

    /**
     * Determine if a wrestler is bookable.
     *
     * @return bool
     */
    public function getIsBookableAttribute()
    {
        return $this->is_hired && !($this->is_retired || $this->is_injured || $this->is_suspended);
    }

    /**
     * Determine if a wrestler is hired.
     *
     * @return bool
     */
    public function getIsHiredAttribute()
    {
        return !is_null($this->hired_at) && $this->hired_at->isPast();
    }

    /**
     * Determine if a wrestler is retired.
     *
     * @return bool
     */
    public function getIsRetiredAttribute()
    {
        return $this->retirements()->whereNull('ended_at')->exists();
    }

    /**
     * Determine if a wrestler is suspended.
     *
     * @return bool
     */
    public function getIsSuspendedAttribute()
    {
        return $this->suspensions()->whereNull('ended_at')->exists();
    }

    /**
     * Determine if a wrestler is injured.
     *
     * @return bool
     */
    public function getIsInjuredAttribute()
    {
        return $this->injuries()->whereNull('ended_at')->exists();
    }


    /**
     * Return the wrestler's height in feet.
     *
     * @return string
     */
    public function getFeetAttribute()
    {
        return floor($this->height / 12);
    }

    /**
     * Return the wrestler's height in inches.
     *
     * @return string
     */
    public function getInchesAttribute()
    {
        return $this->height % 12;
    }

    /**
     * Scope a query to only include bookable wrestlers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeBookable($query)
    {
        return $query->where('hired_at', '<=', now())
                ->whereDoesntHave('retirements', function (Builder $query) {
                    $query->whereNull('ended_at');
                })
                ->whereDoesntHave('injuries', function (Builder $query) {
                    $query->whereNull('ended_at');
                })
                ->whereDoesntHave('suspensions', function (Builder $query) {
                    $query->whereNull('ended_at');
                });
    }

    /**
     * Scope a query to only include inactive wrestlers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeInactive($query)
    {
        return $query->whereNull('hired_at')
                ->orWhere('hired_at', '>', now());
    }

    /**
     * Scope a query to only include retired wrestlers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRetired($query)
    {
        return $query->whereHas('retirements', function ($query) {
            $query->whereNull('ended_at');
        });
    }

    /**
     * Scope a query to only include suspended wrestlers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuspended($query)
    {
        return $query->whereHas('suspensions', function ($query) {
            $query->whereNull('ended_at');
        });
    }

    /**
     * Scope a query to only include injured wrestlers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInjured($query)
    {
        return $query->whereHas('injuries', function ($query) {
            $query->whereNull('ended_at');
        });
    }

    /**
     * Activate a wrestler.
     *
     * @return boolean
     */
    public function activate()
    {
        return $this->update(['hired_at' => now()]);
    }

    /**
     * Retire a wrestler.
     *
     * @return void
     */
    public function retire()
    {
        if ($this->is_suspended) {
            $this->reinstate();
        }

        if ($this->is_injured) {
            $this->recover();
        }

        $this->retirements()->create(['started_at' => now()]);
    }

    /**
     * Unretire a wrestlerl.
     *
     * @return void
     */
    public function unretire()
    {
        return $this->retirement()->update(['ended_at' => now()]);
    }

    /**
     * Suspend a wrestler.
     *
     * @return void
     */
    public function suspend()
    {
        $this->suspensions()->create(['started_at' => now()]);
    }

    /**
     * Reinstate a wrestler.
     *
     * @return void
     */
    public function reinstate()
    {
        $this->suspension()->update(['ended_at' => now()]);
    }

    /**
     * Injure a wrestler.
     *
     * @return void
     */
    public function injure()
    {
        $this->injuries()->create(['started_at' => now()]);
    }

    /**
     * Recover a wrestler.
     *
     * @return void
     */
    public function recover()
    {
        $this->injury()->update(['ended_at' => now()]);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $data                 = parent::toArray();
        $data['status']       = $this->status->label();

        return $data;
    }
}
