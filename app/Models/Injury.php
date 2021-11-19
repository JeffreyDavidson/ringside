<?php

namespace App\Models;

use Concerns\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Injury extends Model
{
    use Unguarded,
        HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'injuries';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
    ];

    /**
     * Retrieve the injured model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function injurable()
    {
        return $this->morphTo();
    }
}
