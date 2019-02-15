<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
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
    protected $dates = ['hired_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->is_active = $model->hired_at->lte(today());
        });
    }

    /**
     * Get the retirements of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retirable');
    }

    /**
     * Get the current retirement of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function retirement()
    {
        return $this->morphOne(Retirement::class, 'retirable')->whereNull('ended_at');
    }

    /**
     * Get the previous retirement of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function previousRetirement()
    {
        return $this->morphOne(Retirement::class, 'retirable')->whereNotNull('ended_at')->latest('started_at');
    }

    /**
     * Retire a wrestler.
     *
     * @return void
     */
    public function retire()
    {
        $this->retirements()->create(['started_at' => today()]);
    }

    /**
     * Check to see if the wrestler is retired.
     *
     * @return bool
     */
    public function isRetired()
    {
        return $this->retirements()->whereNull('ended_at')->exists();
    }

    /**
     * Unretire the retired wrestler.
     *
     * @return void
     */
    public function unretire()
    {
        $this->retirement()->update(['ended_at' => today()]);
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
     * Get the previous suspension of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function previousSuspension()
    {
        return $this->morphOne(Suspension::class, 'suspendable')->whereNotNull('ended_at')->latest('started_at');
    }

    /**
     * Suspend a wrestler.
     *
     * @return void
     */
    public function suspend()
    {
        $this->suspensions()->create(['started_at' => today()]);
    }

    /**
     * Check to see if the wrestler is suspended.
     *
     * @return bool
     */
    public function isSuspended()
    {
        return $this->suspensions()->whereNull('ended_at')->exists();
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
     * Reinstate the suspended wrestler.
     *
     * @return void
     */
    public function reinstate()
    {
        $this->suspension()->update(['ended_at' => today()]);
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
     * Get the previous injuries of the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function previousInjury()
    {
        return $this->morphOne(Injury::class, 'injurable')->whereNotNull('ended_at')->latest('started_at');
    }

    /**
     * Injure a wrestler.
     *
     * @return void
     */
    public function injure()
    {
        $this->injuries()->create(['started_at' => today()]);
    }

    /**
     * Check to see if the wrestler is injured.
     *
     * @return bool
     */
    public function isInjured()
    {
        return $this->injuries()->whereNull('ended_at')->exists();
    }

    /**
     * Recover the injured wrestler.
     *
     * @return void
     */
    public function recover()
    {
        $this->injury()->update(['ended_at' => today()]);
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
     * Check to see if the wrestler is currently active.
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->is_active === true;
    }

    /**
     * Deactivate an active wrestler.
     *
     * @return $this
     */
    public function deactivate()
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Activate an inactive wrestler.
     *
     * @return $this
     */
    public function activate()
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Scope a query to only include active wrestlers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive wrestlers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include wrestlers of a given state.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasState($query, $state)
    {
        $scope = 'scope' . Str::studly($state);

        if (method_exists($this, $scope)) {
            return $this->{$scope}($query);
        }
    }

    /**
     * Get the user belonging to the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
