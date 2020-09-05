<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Employment extends MorphPivot
{
    use Concerns\Unguarded;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employments';

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
    public function employable()
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
