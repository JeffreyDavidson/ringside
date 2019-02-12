<?php

namespace App;

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
    protected $dates = ['deleted_at'];

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
        $this->retirements()->create(['retired_at' => today()]);
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
}
