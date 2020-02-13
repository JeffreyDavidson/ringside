<?php

namespace App\Models;

use App\Enums\RefereeStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use MadWeb\Enum\EnumCastable;

class Referee extends SingleRosterMember
{
    use SoftDeletes,
        EnumCastable,
        Concerns\HasFullName,
        Concerns\CanBeBooked;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => RefereeStatus::class,
    ];
}
