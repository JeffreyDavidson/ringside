<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suspension extends Model
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
     * Retrieve the suspended model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function suspendable()
    {
        return $this->morphTo();
    }
}
