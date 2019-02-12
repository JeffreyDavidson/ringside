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
     * Get the current retirement of the wrestler.
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
     * Check to see if the wrestler is retired.
     *
     * @return bool
     */
    public function unretire()
    {
        $this->retirements()->whereNull('ended_at')->first()->update(['ended_at' => today()]);
    }
}
