<?php

namespace App\Models;

use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Eloquent\Concerns\HasCustomRelationships;

class Stable extends Model
{
    use SoftDeletes,
        HasCachedAttributes,
        HasCustomRelationships,
        Concerns\MemberHistory,
        Concerns\CanBeEmployed;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user belonging to the tag team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public function disassemble()
    {
        $this->currentWrestlers()->detach();
        $this->currentTagteams()->detach();
        $this->touch();

        return $this;
    }
}
