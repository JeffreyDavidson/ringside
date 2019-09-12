<?php

namespace App\Models;

use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use SoftDeletes,
        HasCachedAttributes,
        Concerns\CanBeSuspended,
        Concerns\CanBeInjured,
        Concerns\CanBeRetired,
        Concerns\CanBeEmployed,
        Concerns\CanBeBooked,
        Concerns\HasFullName;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user belonging to the manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
