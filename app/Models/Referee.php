<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Referee extends SingleRosterMember
{
    use SoftDeletes,
        Concerns\HasFullName;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
