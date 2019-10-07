<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Eloquent\Concerns\HasCustomRelationships;

class Wrestler extends SingleRosterMember
{
    use SoftDeletes,
        HasCustomRelationships,
        Concerns\HasAHeight,
        Concerns\CanBeStableMember,
        Concerns\CanBeTagTeamPartner;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user assigned to the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
