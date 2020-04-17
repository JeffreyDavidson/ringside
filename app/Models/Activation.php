<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
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
    protected $dates = ['started_at', 'ended_at'];

    /**
     * Get the owning employed model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function activatable()
    {
        return $this->morphTo();
    }

    /**
     * Retrieve an employment started before a given date.
     *
     * @param  string $date
     * @return boolean
     */
    public function startedBefore($date)
    {
        return $this->started_at->lte($date);
    }
}
