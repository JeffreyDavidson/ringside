<?php

namespace App\Models;

use App\Eloquent\Concerns\HasCustomRelationships;
use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stable extends Model
{
    use SoftDeletes,
        HasCachedAttributes,
        HasCustomRelationships,
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
     * Scope a query to only include suspended models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $this->whereHas('currentSuspension');
    }

    public function disassemble()
    {
        $this->currentWrestlers()->detach();
        $this->currentTagteams()->detach();
        $this->touch();

        return $this;
    }
}
