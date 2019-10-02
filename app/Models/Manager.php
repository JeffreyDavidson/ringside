<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Eloquent\Concerns\HasCustomRelationships;

class Manager extends SingleRosterMember
{
    use SoftDeletes,
        HasCustomRelationships,
        Concerns\HasFullName,
        Concerns\CanBeStableMember;

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
